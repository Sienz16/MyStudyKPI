<?php if(isset($_SESSION['status_message'])): ?>
<div class="modal-overlay" id="statusModal">
    <div class="modal">
        <div class="modal-icon <?php echo $_SESSION['status_message']['type']; ?>">
            <?php if($_SESSION['status_message']['type'] === 'success'): ?>
                <ion-icon name="checkmark-circle-outline"></ion-icon>
            <?php else: ?>
                <ion-icon name="close-circle-outline"></ion-icon>
            <?php endif; ?>
        </div>
        <div class="modal-content">
            <h2 class="modal-title"><?php echo $_SESSION['status_message']['title']; ?></h2>
            <p class="modal-message"><?php echo $_SESSION['status_message']['message']; ?></p>
            <div class="modal-actions">
                <a href="<?php echo $_SESSION['status_message']['return_url']; ?>" class="modal-button primary-button">
                    <ion-icon name="list-outline"></ion-icon>
                    <?php echo $_SESSION['status_message']['return_text'] ?? 'View Records'; ?>
                </a>
                <?php if($_SESSION['status_message']['type'] !== 'success'): ?>
                <button onclick="history.back()" class="modal-button secondary-button">
                    <ion-icon name="arrow-back-outline"></ion-icon>
                    Go Back
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('statusModal');
    if(modal) {
        modal.classList.add('show');
        <?php if($_SESSION['status_message']['type'] === 'success'): ?>
        setTimeout(() => {
            modal.classList.remove('show');
        }, 5000);
        <?php endif; ?>
    }
});
</script>

<?php 
unset($_SESSION['status_message']); 
endif; 
?> 