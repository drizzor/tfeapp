// Script pour le fonctionnement des tableaux
    $(document).ready(function()
    {
        $('.dataTable').DataTable
        ({
            responsive: false,
            "language": 
            {
                "decimal": ",",
                "thousands": ".",
                "lengthMenu": "Affichage de _MENU_ enregistrements par page",
                "zeroRecords": "<i class='fas fa-database fa-2x'></i> <h4>Rien a été trouvé dans la base de données...</h4>",
                "info": "Page _PAGE_ sur _PAGES_",
                "infoEmpty": "Aucune donnée disponible !<br>",
                "infoFiltered": "(Filtré sur un total de _MAX_ entrée(s))",
                "search": "",
                "searchPlaceholder": "Rechercher",
                "paginate": 
                {
                    "first":      "<i class='fas fa-step-backward'></i>",
                    "last":       "<i class='fas fa-step-forward'></i>",
                    "next":       "<i class='fas fa-chevron-right'></i>",
                    "previous":   "<i class='fas fa-chevron-left'></i>"
                }            
            },        

            "columnDefs": [
                {
                    "targets": 'noSort',
                    "orderable": false,
                    "searchable": false
                },
                
                {
                    "targets": [ 0 ],
                    "visible": false,
                    "searchable": false
                }
            ],

            "order": [[0, "desc" ]],            
            
        });
    });    

    $(document).ready(function()
    {
        $('.dataTable_responsive').DataTable
        ({
            responsive: true,
            "language": 
            {
                "decimal": ",",
                "thousands": ".",
                "lengthMenu": "Affichage de _MENU_ enregistrements par page",
                "zeroRecords": "<i class='fas fa-database fa-2x'></i> <h4>Rien a été trouvé dans la base de données...</h4>",
                "info": "Page _PAGE_ sur _PAGES_",
                "infoEmpty": "Aucune donnée disponible !<br>",
                "infoFiltered": "(Filtré sur un total de _MAX_ entrée(s))",
                "search": "",
                "searchPlaceholder": "Rechercher",
                "paginate": 
                {
                    "first":      "<i class='fas fa-step-backward'></i>",
                    "last":       "<i class='fas fa-step-forward'></i>",
                    "next":       "<i class='fas fa-chevron-right'></i>",
                    "previous":   "<i class='fas fa-chevron-left'></i>"
                }            
            },        

            "columnDefs": [
                {
                    "targets": 'noSort',
                    "orderable": false,
                    "searchable": false
                },
                
                {
                    "targets": [ 0 ],
                    "visible": false,
                    "searchable": false
                }
            ],

            "order": [[0, "desc" ]],            
            
        });
    });    