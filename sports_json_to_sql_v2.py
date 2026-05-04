"""
sports_json_to_sql_v2.py
========================
Converts all JSON data files to SQL INSERT statements for the NEW schema
(includes: statements, statement_reactions, contributions, feedback, contact_messages).

Usage:
    python sports_json_to_sql_v2.py

Place this file next to your JSON data files and adjust DATA_FILES paths.
Output: sports_inserts_v2.sql
"""

import json, os, re
from pathlib import Path
from datetime import datetime

# ─── File paths ──────────────────────────────────────────────
DATA_FILES = {
    "athletes":       "athletes.json",
    "clubs":          "clubs.json",
    "egyptian":       "egyptian_champions.json",
    "championships":  "championships.json",
    "players":        "players.json",
    "records":        "records.json",
    "sport_info":     "sport_info.json",
    "compare_sports": "compare_sports.json",
    "dashboard":      "dashboard.json",
}
OUTPUT_FILE = "sports_inserts_v2.sql"

# ─── Helpers ─────────────────────────────────────────────────
def esc(value):
    if value is None: return "NULL"
    if isinstance(value, bool): return "1" if value else "0"
    if isinstance(value, (int, float)): return str(value)
    s = str(value).replace("\\", "\\\\").replace("'", "\\'")
    return f"'{s}'"

def esc_json(obj):
    if obj is None: return "NULL"
    s = json.dumps(obj, ensure_ascii=False).replace("'", "\\'")
    return f"'{s}'"

def load(path):
    with open(path, encoding="utf-8") as f:
        return json.load(f)

def header(title):
    bar = "=" * 60
    return f"\n-- {bar}\n-- {title}\n-- {bar}\n"

def slugify(name):
    return re.sub(r"[^a-z0-9]+", "_", name.lower()).strip("_")

# ─── Generators ──────────────────────────────────────────────
def gen_sport_info(data, lines):
    lines.append(header("SPORTS"))
    for sp in data:
        desc  = json.dumps(sp.get("description", []),  ensure_ascii=False).replace("'", "\\'")
        hist  = json.dumps(sp.get("history",     []),  ensure_ascii=False).replace("'", "\\'")
        rules = json.dumps(sp.get("rules",       []),  ensure_ascii=False).replace("'", "\\'")
        equip = json.dumps(sp.get("equipment",   []),  ensure_ascii=False).replace("'", "\\'")
        lines.append(
            f"INSERT IGNORE INTO sports (name, header_image, description, history, rules, equipment, fact) "
            f"VALUES ({esc(sp['name'])}, {esc(sp.get('headerImage'))}, "
            f"'{desc}', '{hist}', '{rules}', '{equip}', {esc(sp.get('fact'))});"
        )

    lines.append(header("SPORT_STATS"))
    for sp in data:
        for st in sp.get("stats", []):
            lines.append(
                f"INSERT INTO sport_stats (sport_id, stat_name, stat_value) "
                f"SELECT id, {esc(st['name'])}, {esc(st['value'])} "
                f"FROM sports WHERE name = {esc(sp['name'])} LIMIT 1;"
            )

    lines.append(header("SPORT_GALLERY"))
    for sp in data:
        for i, img in enumerate(sp.get("gallery", [])):
            lines.append(
                f"INSERT INTO sport_gallery (sport_id, image_path, sort_order) "
                f"SELECT id, {esc(img)}, {i} FROM sports WHERE name = {esc(sp['name'])} LIMIT 1;"
            )

def gen_compare_sports(data, lines):
    sports = data.get("sports", data) if isinstance(data, dict) else data

    lines.append(header("SPORT_SKILLS"))
    for sp in sports:
        for sk in sp.get("skills", []):
            lines.append(
                f"INSERT INTO sport_skills (sport_id, skill_name, skill_level) "
                f"SELECT id, {esc(sk['name'])}, {esc(sk['level'])} "
                f"FROM sports WHERE name = {esc(sp['name'])} LIMIT 1;"
            )
        if sp.get("image"):
            lines.append(
                f"UPDATE sports SET logo_image = {esc(sp['image'])} "
                f"WHERE name = {esc(sp['name'])} AND logo_image IS NULL;"
            )

    lines.append(header("SPORT_POPULARITY_CHART"))
    for sp in sports:
        chart = sp.get("chart", {})
        for yr, val in zip(chart.get("years", []), chart.get("values", [])):
            lines.append(
                f"INSERT INTO sport_popularity_chart (sport_id, chart_year, chart_value) "
                f"SELECT id, {esc(yr)}, {esc(val)} FROM sports WHERE name = {esc(sp['name'])} LIMIT 1;"
            )

def gen_championships(data, lines):
    lines.append(header("CHAMPIONSHIPS"))
    champs = data.get("championships", data) if isinstance(data, dict) else data
    for sport_name, items in champs.items():
        for ch in items:
            lines.append(
                f"INSERT INTO championships (sport_id, name, image) "
                f"SELECT id, {esc(ch['name'])}, {esc(ch.get('image'))} "
                f"FROM sports WHERE name = {esc(sport_name)} LIMIT 1;"
            )

def gen_clubs(data, lines):
    lines.append(header("CLUBS"))
    items = data if isinstance(data, list) else data.get("clubs", [])
    for cl in items:
        lines.append(
            f"INSERT INTO clubs (name, sport_id, governorate, image) "
            f"SELECT {esc(cl['name'])}, s.id, {esc(cl.get('governorate'))}, {esc(cl.get('image'))} "
            f"FROM sports s WHERE s.name = {esc(cl['sport'])} LIMIT 1;"
        )

def gen_athletes(data, lines):
    athletes = data.get("athletes", data) if isinstance(data, dict) else data

    lines.append(header("COUNTRIES (from athletes)"))
    countries = set(a.get("country") for a in athletes if a.get("country"))
    for c in sorted(countries):
        lines.append(f"INSERT IGNORE INTO countries (name) VALUES ({esc(c)});")

    lines.append(header("ATHLETES"))
    for a in athletes:
        country_sql = (
            f"(SELECT id FROM countries WHERE name = {esc(a['country'])} LIMIT 1)"
            if a.get("country") else "NULL"
        )
        lines.append(
            f"INSERT IGNORE INTO athletes (slug, name, sport_id, country_id, image, banner, chart_about) "
            f"SELECT {esc(a['id'])}, {esc(a['name'])}, s.id, {country_sql}, "
            f"{esc(a.get('image'))}, {esc(a.get('banner'))}, {esc(a.get('chart_about'))} "
            f"FROM sports s WHERE s.name = {esc(a['sport'])} LIMIT 1;"
        )

    lines.append(header("ATHLETE_STATS"))
    for a in athletes:
        for st in a.get("stats", []):
            lines.append(
                f"INSERT INTO athlete_stats (athlete_id, stat_label, stat_value) "
                f"SELECT id, {esc(st['label'])}, {esc(st['value'])} "
                f"FROM athletes WHERE slug = {esc(a['id'])} LIMIT 1;"
            )

    lines.append(header("ATHLETE_CHART"))
    for a in athletes:
        for yr, val in zip(a.get("chart_years", []), a.get("chart", [])):
            lines.append(
                f"INSERT INTO athlete_chart (athlete_id, chart_year, chart_value) "
                f"SELECT id, {esc(yr)}, {esc(val)} FROM athletes WHERE slug = {esc(a['id'])} LIMIT 1;"
            )

    lines.append(header("ATHLETE_TIMELINE"))
    for a in athletes:
        for ev in a.get("timeline", []):
            lines.append(
                f"INSERT INTO athlete_timeline (athlete_id, event_year, event_text) "
                f"SELECT id, {esc(ev['year'])}, {esc(ev['event'])} "
                f"FROM athletes WHERE slug = {esc(a['id'])} LIMIT 1;"
            )

def gen_egyptian(data, lines):
    lines.append(header("EGYPTIAN CHAMPIONS"))
    items = data if isinstance(data, list) else data.get("champions", [])
    lines.append("INSERT IGNORE INTO countries (name) VALUES ('Egypt');")
    for ch in items:
        slug = slugify(ch["name"])
        lines.append(
            f"INSERT INTO athletes (slug, name, sport_id, country_id, image, is_egyptian_champion, champion_year, achievements) "
            f"SELECT {esc(slug)}, {esc(ch['name'])}, s.id, "
            f"(SELECT id FROM countries WHERE name='Egypt' LIMIT 1), "
            f"{esc(ch.get('image'))}, 1, {esc(ch.get('year'))}, {esc(ch.get('achievements'))} "
            f"FROM sports s WHERE s.name = {esc(ch['sport'])} LIMIT 1 "
            f"ON DUPLICATE KEY UPDATE is_egyptian_champion=1, champion_year={esc(ch.get('year'))}, "
            f"achievements={esc(ch.get('achievements'))};"
        )

def gen_players(data, lines):
    lines.append(header("PLAYERS (banner cards)"))
    items = data if isinstance(data, list) else data.get("players", [])
    for pl in items:
        slug = slugify(pl["name"])
        country_sql = (
            f"(SELECT id FROM countries WHERE name = {esc(pl['country'])} LIMIT 1)"
            if pl.get("country") else "NULL"
        )
        lines.append(f"INSERT IGNORE INTO countries (name) VALUES ({esc(pl.get('country', ''))});")
        lines.append(
            f"INSERT IGNORE INTO athletes (slug, name, sport_id, country_id, banner) "
            f"SELECT {esc(slug)}, {esc(pl['name'])}, s.id, {country_sql}, {esc(pl.get('image'))} "
            f"FROM sports s WHERE s.name = {esc(pl['sport'])} LIMIT 1;"
        )
        lines.append(
            f"UPDATE athletes SET banner = {esc(pl.get('image'))} "
            f"WHERE slug = {esc(slug)} AND (banner IS NULL OR banner = '');"
        )

def gen_records(data, lines):
    lines.append(header("COUNTRIES (from records)"))
    items = data if isinstance(data, list) else data.get("records", [])
    for r in items:
        if r.get("country"):
            lines.append(f"INSERT IGNORE INTO countries (name) VALUES ({esc(r['country'])});")

    lines.append(header("RECORDS"))
    for r in items:
        details = r.get("details", {})
        extra_keys = {"achievements", "specialties", "classic_wins"}
        extra = {k: v for k, v in details.items() if k in extra_keys}
        country_sql = (
            f"(SELECT id FROM countries WHERE name = {esc(r['country'])} LIMIT 1)"
            if r.get("country") else "NULL"
        )
        lines.append(
            f"INSERT INTO records (sport_id, specialty, athlete_name, record_text, record_date, "
            f"country_id, age, team, height, weight, career_wins, world_ranking, "
            f"olympic_golds, world_championships, olympic_medals, world_cup_wins, is_retired, extra_json) "
            f"SELECT s.id, {esc(r.get('specialty'))}, {esc(r['athlete'])}, {esc(r['record'])}, "
            f"{esc(r.get('date'))}, {country_sql}, "
            f"{esc(details.get('age'))}, {esc(details.get('team'))}, "
            f"{esc(details.get('height'))}, {esc(details.get('weight'))}, "
            f"{esc(details.get('career_wins'))}, {esc(details.get('world_ranking'))}, "
            f"{esc(details.get('olympic_golds'))}, {esc(details.get('world_championships'))}, "
            f"{esc(details.get('olympic_medals'))}, {esc(details.get('world_cup_wins'))}, "
            f"{esc(details.get('retired', False))}, "
            f"{esc_json(extra) if extra else 'NULL'} "
            f"FROM sports s WHERE s.name = {esc(r['category'])} LIMIT 1;"
        )

def gen_dashboard(data, lines):
    overview = data.get("overview", {})

    lines.append(header("DASHBOARD_OVERVIEW"))
    for yr, rc in zip(overview.get("years", []), overview.get("records", [])):
        lines.append(f"INSERT IGNORE INTO dashboard_overview (year, records_count) VALUES ({esc(yr)}, {esc(rc)});")

    lines.append(header("DASHBOARD_SPORT_STATS (popularity)"))
    pop = overview.get("popularity", {})
    for label, val in zip(pop.get("labels", []), pop.get("values", [])):
        lines.append(f"INSERT IGNORE INTO sports (name) VALUES ({esc(label)});")
        lines.append(
            f"INSERT INTO dashboard_sport_stats (sport_id, popularity_score) "
            f"SELECT id, {esc(val)} FROM sports WHERE name = {esc(label)} LIMIT 1 "
            f"ON DUPLICATE KEY UPDATE popularity_score = {esc(val)};"
        )

    lines.append(header("DASHBOARD_RANKING"))
    for rk in data.get("ranking", []):
        country_sql = (
            f"(SELECT id FROM countries WHERE name = {esc(rk['country'])} LIMIT 1)"
            if rk.get("country") else "NULL"
        )
        lines.append(f"INSERT IGNORE INTO countries (name) VALUES ({esc(rk.get('country',''))});")
        lines.append(
            f"INSERT INTO dashboard_ranking (rank, athlete_name, sport_id, metric, metric_year, country_id) "
            f"SELECT {esc(rk['rank'])}, {esc(rk['athlete'])}, s.id, "
            f"{esc(rk.get('metric'))}, {esc(rk.get('year'))}, {country_sql} "
            f"FROM sports s WHERE s.name = {esc(rk['sport'])} LIMIT 1;"
        )

    lines.append(header("DASHBOARD_SPORT_STATS (full cards)"))
    for sp in data.get("sports", []):
        stats = sp.get("stats", {})
        lines.append(f"INSERT IGNORE INTO sports (name) VALUES ({esc(sp['name'])});")
        lines.append(
            f"INSERT INTO dashboard_sport_stats "
            f"(sport_id, popularity_score, country, stat_year, total_players, professional_leagues, world_cup_years) "
            f"SELECT s.id, {esc(sp.get('popularity'))}, {esc(sp.get('country'))}, "
            f"{esc(sp.get('year'))}, {esc(stats.get('totalPlayers'))}, "
            f"{esc(stats.get('professionalLeagues'))}, {esc(stats.get('worldCupYears'))} "
            f"FROM sports s WHERE s.name = {esc(sp['name'])} LIMIT 1 "
            f"ON DUPLICATE KEY UPDATE popularity_score={esc(sp.get('popularity'))}, "
            f"total_players={esc(stats.get('totalPlayers'))}, "
            f"professional_leagues={esc(stats.get('professionalLeagues'))};"
        )

    lines.append(header("DASHBOARD_TOP_PLAYERS"))
    for sp in data.get("sports", []):
        for pl in sp.get("topPlayers", []):
            metrics = pl.get("metrics", {})
            country_sql = (
                f"(SELECT id FROM countries WHERE name = {esc(pl['country'])} LIMIT 1)"
                if pl.get("country") else "NULL"
            )
            lines.append(f"INSERT IGNORE INTO countries (name) VALUES ({esc(pl.get('country',''))});")
            lines.append(
                f"INSERT INTO dashboard_top_players "
                f"(sport_id, rank, player_name, country_id, age, stat_year, goals, assists, matches) "
                f"SELECT s.id, {esc(pl['rank'])}, {esc(pl['name'])}, "
                f"{country_sql}, {esc(pl.get('age'))}, {esc(pl.get('year'))}, "
                f"{esc(metrics.get('goals'))}, {esc(metrics.get('assists'))}, {esc(metrics.get('matches'))} "
                f"FROM sports s WHERE s.name = {esc(sp['name'])} LIMIT 1;"
            )

# ─── Main ────────────────────────────────────────────────────
def main():
    lines = [
        "-- Auto-generated by sports_json_to_sql_v2.py",
        f"-- Generated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}",
        "-- Schema version: v2 (includes statements, contributions, feedback, contact_messages)",
        "SET FOREIGN_KEY_CHECKS = 0;",
        "SET NAMES utf8mb4;",
        "",
    ]

    GENERATORS = [
        ("sport_info",     gen_sport_info),
        ("compare_sports", gen_compare_sports),
        ("championships",  gen_championships),
        ("clubs",          gen_clubs),
        ("athletes",       gen_athletes),
        ("egyptian",       gen_egyptian),
        ("players",        gen_players),
        ("records",        gen_records),
        ("dashboard",      gen_dashboard),
    ]

    for key, func in GENERATORS:
        path = DATA_FILES.get(key, "")
        if not os.path.exists(path):
            lines.append(f"\n-- ⚠ FILE NOT FOUND: {path}  (skipping {key})")
            print(f"[SKIP] {path}")
            continue
        print(f"[OK]   {path}")
        func(load(path), lines)

    lines.append("\nSET FOREIGN_KEY_CHECKS = 1;")
    lines.append("-- END")

    with open(OUTPUT_FILE, "w", encoding="utf-8") as f:
        f.write("\n".join(lines))

    print(f"\n✅  Written to: {OUTPUT_FILE}  ({len(lines)} lines)")

if __name__ == "__main__":
    main()
