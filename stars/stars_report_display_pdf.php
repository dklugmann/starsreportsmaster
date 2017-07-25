<?
    include("stars_config.php");
    include("stars_functions.php");
    $interppage = $_GET['interppage'];
    $content = loadContents($interppage);
    header('Content-type: application/pdf');
    header('Content-Disposition: filename="downloadedreport.pdf"');
    echo $content;
?>
