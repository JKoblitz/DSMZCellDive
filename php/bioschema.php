<?php

include_once "_db.php";

function print_schema($json)
{
    echo '<script type="application/ld+json">';
    echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    echo '</script>';
}


function schema_cellline(array $cell)
{
    global $db;
    $id = $cell["cell_id"];
    $keywords = ["cell line", $cell['cell_type'], $cell['species']];
    $data = ["Cell line morphology"];

    $stmt = $db->prepare(
        "SELECT 'COI DNA barcodes', COUNT(*) FROM coi WHERE cell_id=?
        UNION
        SELECT 'Short tandem repeats', COUNT(*) FROM str_meta WHERE cell_id=?
        UNION
        SELECT 'Transcriptome data', COUNT(*) FROM ngs WHERE cell_id=?
        UNION
        SELECT 'HLA typing', COUNT(*) FROM hla WHERE cell_id=?
        ");
    $stmt->execute([$id,$id,$id,$id]);
    $datasets = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    foreach ($datasets as $key => $value) {
        if ($value != 0) $data[] = $key;
    }

    $json = array(
        "@context" => "https://schema.org",
        "@type" => "Dataset",
        "http://purl.org/dc/terms/conformsTo" => array(
            "@id" => "https://bioschemas.org/profiles/Dataset",
            "@type" => "CreativeWork"
        ),
        "identifier" => array(
            "@type" => "PropertyValue",
            "name" => "DSMZ ACC number",
            "value" => "ACC-$id"
        ),
        "name" => "$cell[cellline]",
        "url" => "https://celldive.dsmz.de/cellline/ACC-$id",
        // "license" => "http://creativecommons.org/licenses/by-nc/4.0/",
        "creator" => array(
            "@type" => "Organization",
            "name" => "DSMZCellDive"
        ),
        "description" => ucfirst($cell['species']). " cell line ". $cell['cellline'] ,
        "keywords" => $keywords,
        "variableMeasured" => $data,
        "includedInDataCatalog" => array(
            "@type" => "DataCatalog",
            "name" => "DSMZCellDive",
            "description" => "DSMZ Tools for Diving into Cell Line Data",
            "url" => "https://celldive.dsmz.de/",
            "provider" => array(
                "@type" => "Organization",
                "name" => "Leibniz-Institut DSMZ-Deutsche Sammlung von Mikroorganismen und Zellkulturen GmbH",
                "telephone" => "+49 (0) 531-2616-0",
                "faxNumber" => "+49 (0) 531-2616-418",
                "url" => "https://dsmz.de/",
                "vatID" => "DE 114815269",
                "address" => array(
                    "@type" => "PostalAddress",
                    "streetAddress" => "Inhoffenstraße 7 B",
                    "postalCode" => "38124",
                    "addressLocality" => "Braunschweig, Germany"
                )
            )
        ),
        // "citation" => TODO!
    );

   
    return $json;
}
