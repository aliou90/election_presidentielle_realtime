<div class="sidebar">
      <ul class="nav flex-column" id="regionsList">
        <?php include 'sidebar_cer.php'; ?>
          <!-- Les régions et leurs départements seront ajoutés ici -->
      </ul>
</div>

<main class="main-content">
<h1>Commission Électorale Régionale<?= isset($departement['region']) ? ' de ' . $departement['region'] : ''; ?></h1>

</main>

