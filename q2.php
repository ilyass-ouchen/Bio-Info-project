<html>
    <head><h1>Informations related to your accession number !</h1></head>
    <body bgcolor=lightblue>

        <?php
            if(isset($_REQUEST['accession']) && $_REQUEST['accession'] != ""){ // On vérifie que le paramètre du formulaire existe et n'est pas vide
                $ac = $_REQUEST['accession'];  // Recuperer dans des variables locales les parametres du formulaire
            
                $connexion = oci_connect('c##iouchen_a', 'iouchen_a', 'dbinfo'); 
                
                // Plusieurs requêtes pour les différentes informations demandées
                $txtReq1 = " select seq, seqLength, seqMass from proteins p where p.accession = :acces ";
                $txtReq1b = " select specie from entries e where e.accession = :acces";
                $txtReq2 = "select prot_name, name_kind, name_type from protein_names pn, prot_name_2_prot pp where pn.prot_name_id = pp.prot_name_id and pp.accession = :acces";
                $txtReq3 = "select gene_name, name_type from gene_names gn, entry_2_gene_name egn where gn.gene_name_id = egn.gene_name_id and egn.accession = :acces";
                $txtReq4 = "select kw_label from keywords k, entries_2_keywords ek where k.kw_id = ek.kw_id and ek.accession = :acces";
                $txtReq5 = "select txt_c from comments c  where c.accession = :acces"; 
                $txtReq6 = "select db_type from dbref d where d.accession = :acces and db_ref = 'GO' ";

                //echo "<i>(debug : ".$txtReq.")</i><br>";

                $ordre = oci_parse($connexion, $txtReq1b);
                oci_bind_by_name($ordre, ":acces", $ac);
                // Exécution de la requête
                oci_execute($ordre);
                echo '<h2>Specie informations</h2>'; // On met à chaque partie le titre de celle-ci pour indiquer le sujet dont on va afficher les informations
                while (($row = oci_fetch_array($ordre, OCI_BOTH)) !=false)
                {
                    $specie = $row[0]; // On met dans une variable locale la valeur de l'espèce pour ensuite l'utiliser dans l'url, afin de rediriger vers l'espèce dont il est question'
                    echo 'Specie : ' . $row[0];
                    echo " <a href=https://www.ncbi.nlm.nih.gov/Taxonomy/Browser/wwwtax.cgi?id=$specie>Informations concerning the specie</a>";
                    echo '<br>';
                }
                oci_free_statement($ordre);
    

                $ordre = oci_parse($connexion, $txtReq1);
                oci_bind_by_name($ordre, ":acces", $ac);
                // Exécution de la requête
                oci_execute($ordre);
                echo '<h2>Sequence informations</h2>';
                while (($row = oci_fetch_array($ordre, OCI_BOTH)) !=false)
                {
                    echo 'Content : <br>' . $row[0]->read(2000); // le read est utilisé car le type de sequence est clob, le texte est trop long pour un string classique
                    echo '<br><br> Sequence length : ' . $row[1];
                    echo '<br><br> Sequence mass : ' . $row[2];
                }
                oci_free_statement($ordre);

                
                $ordre = oci_parse($connexion, $txtReq2);
                oci_bind_by_name($ordre, ":acces", $ac);
                oci_execute($ordre);
                echo '<br><br><h2>Protein informations</h2>';
                while (($row = oci_fetch_array($ordre, OCI_BOTH)) !=false)
                {
                    echo '<br><br> Name : ' . $row[0];
                    echo '<br> Name kind  : ' . $row[1];
                    echo '<br> Name type : ' . $row[2];
                    echo '<br>';
                }
                oci_free_statement($ordre);

                
                $ordre = oci_parse($connexion, $txtReq3);
                oci_bind_by_name($ordre, ":acces", $ac);
                oci_execute($ordre);
                echo '<br><br><h2>Gene informations</h2>';
                while (($row = oci_fetch_array($ordre, OCI_BOTH)) !=false)
                {
                    echo '<br><br> Name : ' . $row[0];
                    echo '<br> Name type : ' . $row[1];
                    echo '<br>';
                }
                oci_free_statement($ordre);
                
                $ordre = oci_parse($connexion, $txtReq4);
                oci_bind_by_name($ordre, ":acces", $ac);
                oci_execute($ordre);
                echo '<br><br><h2>Keywords</h2>';
                while (($row = oci_fetch_array($ordre, OCI_BOTH)) !=false)
                {
                    echo '<br><br> Label : ' . $row[0];
                    echo '<br>';
                }
                oci_free_statement($ordre);


                $ordre = oci_parse($connexion, $txtReq5);
                oci_bind_by_name($ordre, ":acces", $ac);
                oci_execute($ordre);
                echo '<br><br><h2>Comments</h2>';
                while (($row = oci_fetch_array($ordre, OCI_BOTH)) !=false)
                {
                    echo '<br><br> Text : ' . $row[0];
                    echo '<br>';
                }
                oci_free_statement($ordre);

                
                $ordre = oci_parse($connexion, $txtReq6);
                oci_bind_by_name($ordre, ":acces", $ac);
                oci_execute($ordre);
                echo '<br><br><h2>References to GO</h2>';
                while (($row = oci_fetch_array($ordre, OCI_BOTH)) !=false)
                {
                    $term = $row[0]; // Pareillement à l'espèce, on définit une variable locale avec la valeur du terme pour l'insérer dans le lien qui mènera à la page correspondante aux informations nécessaires
                    echo '<br><br> Type : ' . $term;
                    echo " <a href=https://www.ebi.ac.uk/QuickGO/term/$term>Informations concerning the term</a>";
                    echo '<br>';
                }
                oci_free_statement($ordre);


                oci_close($connexion);           
            }
            else   // S'il n'existe pas ou est vide, on demande d'entrer un numéro d'acession
                echo '<br> Veuillez entrer un numéro accession'
        ?>
    </body>
</html>