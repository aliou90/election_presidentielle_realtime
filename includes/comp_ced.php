<div class="sidebar">
      <ul class="nav flex-column" id="regionsList">
        <?php include 'sidebar_ced.php'; ?>
          <!-- Les régions et leurs départements seront ajoutés ici -->
      </ul>
</div>

<main class="main-content">
<h1>Commission Électorale Départementale <?= isset($commune['departement']) ? ' de ' . $commune['departement'] : ''; ?></h1>
</main>