<html>
    <head><h1>Informations related to your informations</h1></head>
    <body bgcolor=lightblue>
        <?php
            // Recuperer dans des variables locales les parametres du formulaire
            if(isset($_REQUEST['gene']) && isset($_REQUEST['prot']) && isset($_REQUEST['commente'])){ // On vérifie que le paramètre existe et n'est pas vide
                $ge = $_REQUEST['gene']; 
                $pr = $_REQUEST['prot']; 
                $com = $_REQUEST['commente']; 

            
                $connexion = oci_connect('c##iouchen_a', 'iouchen_a', 'dbinfo'); 

                /*
                $txtReq1 = "(select accession from entries e NATURAL JOIN entry_2_gene_name gg NATURAL JOIN gene_names g where g.gene_name LIKE :gene) INTERSECT 
                (select accession from entries e NATURAL JOIN prot_name_2_prot pp NATURAL JOIN protein_names p where p.prot_name LIKE :prot) INTERSECT 
                (select accession from entries e NATURAL JOIN  comments c where c.txt_c LIKE :commente)";
                */

                $txtReq1 = "select distinct accession from entry_2_gene_name NATURAL JOIN gene_names g where g.gene_name LIKE :gene and accession in
                (select accession from prot_name_2_prot NATURAL JOIN protein_names p where p.prot_name LIKE :prot and accession in
                (select accession from comments c where c.txt_c LIKE :commente))";

                //echo "<i>(debug : ".$txtReq.")</i><br>";
    
                $ordre = oci_parse($connexion, $txtReq1);

                $gen = '%'.$ge.'%'; // On définit de nouvelles variables avec les pourcentages permettant, une fois insérées dans la requête, de repérer la présence d'une chaine dans un texte (et pas uniquement au début ou à la fin)
                $prn = '%'.$pr.'%';
                $comn = '%'.$com.'%';

                oci_bind_by_name($ordre, ":gene", $gen);
                oci_bind_by_name($ordre, ":prot", $prn);
                oci_bind_by_name($ordre, ":commente", $comn);
                // Exécution de la requête
                oci_execute($ordre);
                echo '<h2>Entries</h2>';
                echo '<table border="1"> <tr> <th>Entries accessions</th></tr>'; // On crée le tableau des informations
                while (($row = oci_fetch_array($ordre, OCI_BOTH)) !=false){
                    echo '<tr>'; // On crée une nouvelle ligne à chaque tour de boucle
                    echo '<th>'; // On crée une nouvelle cellule de tableau
                    echo "<a href=q2.php?accession=$row[0]>$row[0]</a>"; // Lien vers les informations de l'autre fichier php pour chaque accession, en passant comme paramètre la valeur de l'accession
                    echo '</tr><br>';
                }
                oci_free_statement($ordre);
                oci_close($connexion);     
            }         
        ?>
    </body>
</html>
