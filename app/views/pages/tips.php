
<?php
$extraCss = ['tips.css']; 
?>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

<div style="max-width:1100px;margin:2rem auto;padding:0 2rem;">
    <h1 style="font-size:2.5rem;font-weight:900;margin-bottom:.5rem;">💡 Tips &amp; Recommendations</h1>
    <p style="color:#9aa3a6;margin-bottom:2rem;">Expert guidance to help you improve in every sport.</p>

    <div class="grid">

        <?php
        $cards = [
            [
                "icon" => "fitness_center",
                "title" => "Optimize Your Training",
                "desc" => "Build strength, endurance, and athletic balance.",
                "details" => "Focus on compound exercises like squats, deadlifts, and bench presses. Train each muscle group at least once per week. Track progress and increase intensity."
            ],
            [
                "icon" => "restaurant",
                "title" => "Fuel Your Performance",
                "desc" => "Nutrition, hydration, and energy balance.",
                "details" => "Eat balanced meals with protein, carbs, and healthy fats. Drink water consistently. Avoid processed food."
            ],
            [
                "icon" => "bedtime",
                "title" => "Master Recovery",
                "desc" => "Sleep, rest, and regeneration.",
                "details" => "Sleep 7–9 hours. Stretch after workouts. Growth happens during recovery."
            ],
            [
                "icon" => "psychology",
                "title" => "Mental Preparation",
                "desc" => "Mindset, focus, and confidence.",
                "details" => "Visualize success. Stay consistent. Train discipline."
            ],
            [
                "icon" => "healing",
                "title" => "Injury Prevention",
                "desc" => "Protection and longevity.",
                "details" => "Warm up properly. Never train through pain. Technique > weight."
            ],
            [
                "icon" => "timer",
                "title" => "Time Management",
                "desc" => "Balance life and fitness.",
                "details" => "Schedule workouts. Track habits. Consistency wins."
            ],
        ];
        ?>

        <?php foreach ($cards as $card): ?>
            <div class="card">
                <span class="material-symbols-outlined icon"><?= $card['icon'] ?></span>

                <h2><?= htmlspecialchars($card['title']) ?></h2>

                <p><?= htmlspecialchars($card['desc']) ?></p>

                <a class="toggle">Read More</a>

                <div class="card-details">
                    <p><?= htmlspecialchars($card['details']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>

    <!-- </div> -->

    <?php $tips = [
        'Football'     => ['Warm up for at least 10 minutes before training.','Focus on passing accuracy before speed.','Watch professional matches to study positioning.','Train both feet equally for balanced play.'],
        'Basketball'   => ['Work on your weak hand dribbling daily.','Box out on every rebound opportunity.','Communicate constantly with teammates.','Study opponents\' tendencies before games.'],
        'Tennis'       => ['Master the basics: grip, stance, and swing.','Use your whole body rotation when serving.','Practice footwork as much as strokes.','Stay mentally focused between points.'],
        'Swimming'     => ['Develop a streamlined push-off technique.','Breathe every 3 strokes for balanced form.','Kick from the hips, not the knees.','Track lap times and improve each session.'],
        'Cycling'      => ['Maintain 80–100 rpm cadence for efficiency.','Use nutrition during rides over 90 minutes.','Keep your upper body relaxed to save energy.','Adjust saddle height to prevent knee injury.'],
        'Boxing'       => ['Keep hands up and chin down at all times.','Move your head after throwing combinations.','Build cardio with jump rope daily.','Master the jab before other punches.'],
        'Bodybuilding' => ['Progressive overload is key to muscle growth.','Prioritise sleep — muscles grow during recovery.','Track protein intake every day.','Use full range of motion on every rep.'],
        'Athletics'    => ['Incorporate interval training for speed gains.','Focus on form before increasing distance.','Rest days are as important as training days.','Hydrate before, during, and after sessions.'],
    ]; ?>

    <!-- <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.25rem;"> -->
        <?php foreach ($tips as $sport => $items): ?>
        <div  class= "card" >
            <h3 style="font-size:1rem;font-weight:700;color:#0da6f2;margin-bottom:.85rem;"><?= $sport ?></h3>
            <ul style="list-style:disc;padding-left:1.1rem;display:flex;flex-direction:column;gap:.6rem;">
                <?php foreach ($items as $tip): ?>
                <li style="color:#9aa3a6;font-size:.875rem;line-height:1.5;"><?= htmlspecialchars($tip) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<script>
const cards = document.querySelectorAll(".card");
const grid = document.querySelector(".grid");

cards.forEach(card => {
  const toggle = card.querySelector(".toggle");

  toggle.addEventListener("click", e => {
    e.stopPropagation();
    toggleCard(card);
  });

  card.addEventListener("click", () => {
    toggleCard(card);
  });
});

function toggleCard(card) {
  const isOpen = card.classList.contains("active");

  cards.forEach(c => c.classList.remove("active"));
  grid.classList.remove("dim");

  if (!isOpen) {
    card.classList.add("active");
    grid.classList.add("dim");
    card.querySelector(".toggle").textContent = "Read Less";
  } else {
    card.querySelector(".toggle").textContent = "Read More";
  }

  cards.forEach(c => {
    if (c !== card) {
      c.querySelector(".toggle").textContent = "Read More";
    }
  });
}
</script>
