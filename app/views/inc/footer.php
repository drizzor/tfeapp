</div>
</div>
<script src="<?= URLROOT; ?>/js/lib/jQuery.js"></script>
<script src="<?= URLROOT; ?>/js/lib/popper.js"></script>
<script src="<?= URLROOT; ?>/js/lib/bootstrap.js"></script>
<script src="<?= URLROOT; ?>/js/lib/dataTables.js"></script>
<script src="<?= URLROOT; ?>/js/lib/dataTables.bootstrap.js"></script>
<script> let urlAuto = "<?= URLROOT . '/customers/autocomplete' ?>"; </script>
<script src="<?= URLROOT; ?>/js/autocomplete_customer.js"></script>
<script src="<?= URLROOT; ?>/js/main.js"></script>
<script src="<?= URLROOT; ?>/js/invoice.js"></script>
<script src="<?= URLROOT; ?>/js/tableSort.js"></script>
<script src="<?= URLROOT; ?>/js/ajaxValidator.js"></script>
<script src="<?= URLROOT; ?>/js/lib/baguetteBox.min.js"></script>
<script> baguetteBox.run('.compact-gallery'); </script>
<!-- Compteur textarea -->
<script>
    var text_max = 255;
    $('#textarea_feedback').html(text_max + ' caractères restant');

    $('#textarea_count').keyup(function() {
        var text_length = $('#textarea_count').val().length;
        var text_remaining = text_max - text_length;

        $('#textarea_feedback').html(text_remaining + ' caractères restant');
    });
</script>
</body>
</html>