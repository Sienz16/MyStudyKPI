<?php if(isset($_SESSION['status_message']) && $_SESSION['status_message']['type'] === 'error'): ?>
<div class="modal-overlay" id="statusModal">
    <div class="modal">
        <div class="modal-icon error">
            <ion-icon name="close-circle-outline" style="color: #dc3545;"></ion-icon>
        </div>
        <div class="modal-content">
            <h2 class="modal-title" style="color: red;">
                <?php echo $_SESSION['status_message']['title']; ?>
            </h2>
            <p class="modal-message">
                <?php echo $_SESSION['status_message']['message']; ?>
            </p>
            <div class="modal-actions">
                <a href="<?php echo $_SESSION['status_message']['return_url']; ?>" class="modal-button secondary-button">
                    <ion-icon name="arrow-back-outline"></ion-icon>
                    <?php echo $_SESSION['status_message']['return_text']; ?>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('statusModal');
    if(modal) {
        modal.classList.add('show');
        setTimeout(() => {
            modal.classList.remove('show');
        }, 5000);
    }
});
</script>

<?php 
unset($_SESSION['status_message']); 
endif; 
?> 