<script src="<?= URLROOT; ?>/js/lib/jQuery.js"></script>
<script src="<?= URLROOT; ?>/js/lib/popper.js"></script>
<script src="<?= URLROOT; ?>/js/lib/bootstrap.js"></script>
<script src="<?= URLROOT; ?>/js/lib/dataTables.js"></script>
<script src="<?= URLROOT; ?>/js/lib/dataTables.bootstrap.js"></script>
<script> let urlAuto = "<?= URLROOT . '/cities/autocomplete' ?>"; </script>
<script src="<?= URLROOT; ?>/js/autocomplete.js"></script>
<script src="<?= URLROOT; ?>/js/main.js"></script>
<script src="<?= URLROOT; ?>/js/tableSort.js"></script>
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
