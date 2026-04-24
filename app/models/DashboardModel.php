<?php
class DashboardModel extends Model
{
    public function getOverview(): array
    {
        return $this->fetchAll('SELECT year, records_count FROM dashboard_overview ORDER BY year');
    }

    public function getPopularity(): array
    {
        return $this->fetchAll(
            'SELECT s.name AS sport_name, dss.popularity_score
             FROM dashboard_sport_stats dss
             JOIN sports s ON s.id = dss.sport_id
             ORDER BY dss.popularity_score DESC'
        );
    }

    public function getRanking(): array
    {
        return $this->fetchAll(
            'SELECT dr.rank, dr.athlete_name, dr.metric, dr.metric_year,
                    s.name AS sport_name, c.name AS country_name
             FROM dashboard_ranking dr
             LEFT JOIN sports s ON s.id = dr.sport_id
             LEFT JOIN countries c ON c.id = dr.country_id
             ORDER BY dr.rank'
        );
    }

    /** Returns sport cards with full sport data (header_image, logo_image, card_image, etc.) */
    public function getSportCards(): array
    {
        return $this->fetchAll(
            'SELECT s.id AS sport_id, s.name AS sport_name,
                    s.header_image, s.logo_image,s.card_image, s.fact,
                    dss.popularity_score, dss.total_players,
                    dss.professional_leagues, dss.world_cup_years
             FROM dashboard_sport_stats dss
             JOIN sports s ON s.id = dss.sport_id
             ORDER BY dss.popularity_score DESC'
        );
    }

    public function getTopPlayers(): array
    {
        $rows = $this->fetchAll(
            'SELECT dtp.rank, dtp.player_name, dtp.age, dtp.stat_year,
                    dtp.goals, dtp.assists, dtp.matches,
                    s.name AS sport_name, c.name AS country_name
             FROM dashboard_top_players dtp
             JOIN sports s ON s.id = dtp.sport_id
             LEFT JOIN countries c ON c.id = dtp.country_id
             ORDER BY s.name, dtp.rank'
        );
        $grouped = [];
        foreach ($rows as $row) $grouped[$row['sport_name']][] = $row;
        return $grouped;
    }
}
