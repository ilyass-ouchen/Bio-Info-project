--requete 1:
SELECT accession, prot_name FROM
entries E NATURAL JOIN protein_names P NATURAL JOIN Prot_name_2_prot PN NATURAL JOIN Comments C
WHERE C.txt_c LIKE '%cardiac%' ;


--requete 2 :
SELECT accession, prot_name from
entries E NATURAL JOIN protein_names P NATURAL JOIN Prot_name_2_prot PN NATURAL JOIN keywords K
where K.kw_label LIKE '%Long QT syndrome%';


--requete 3 :                                                                    
select accession from entries e NATURAL JOIN proteins p
where seqLength=(Select max(p.seqLength) from proteins p);


--requete 4 :                                                                    
select accession, count(gene_names.gene_name_id)
from entry_2_gene_name, gene_names
group by accession
having count(gene_names.gene_name_id) >2;

--requete 5 :                                                                    
select accession, prot_name, name_kind
from Protein_names P, Prot_name_2_prot PN
where P.prot_name_id = PN.prot_name_id
and P.prot_name like '%channel%';

-- requete 6 :                                                                   
select accession
from entries_2_keywords K, keywords
where kw_label like  '%Long QT syndrome%'
and kw_label like '%Short QT syndrome%' ;


--requete 7 :
select db_ref from dbref DB, entries_2_keywords KK, keywords K
where DB.accession = KK.accession and K.kw_id= KK.kw_id and  K.kw_label LIKE '%Long QT syndrome%' group by DB.db_ref
having count(DB.accession)>=2;



