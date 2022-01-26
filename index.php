<?php

session_start();

if ($_SERVER['SERVER_NAME'] == 'testserver' || $_SERVER['SERVER_NAME'] == 'localhost') {
  define('ROOTPATH', '/celldive2');
} elseif ($_SERVER['SERVER_NAME'] == '172.18.251.4' || $_SERVER['SERVER_NAME'] == 'bacmedia.dsmz.local') {
  define('ROOTPATH', '');
} else {
  define('ROOTPATH', '');
}

define('BASEPATH', $_SERVER['DOCUMENT_ROOT'] . ROOTPATH);
include_once BASEPATH . "/php/Route.php";

Route::add('/', function () {
  include BASEPATH . "/header.php";
  include BASEPATH . "/main.php";
  include BASEPATH . "/dashboard.php";
  include BASEPATH . "/projects.php";
  include BASEPATH . "/footer.php";
});

Route::add('/index.php', function () {
  include BASEPATH . "/header.php";
  include BASEPATH . "/main.php";
  include BASEPATH . "/dashboard.php";
  include BASEPATH . "/projects.php";
  include BASEPATH . "/footer.php";
});

Route::add('/imprint', function () {
  $breadcrumb = [
    ['name' => "Imprint"]
  ];
  include BASEPATH . "/header.php";
  include BASEPATH . "/imprint.html";
  include BASEPATH . "/footer.php";
});

Route::add('/documentation', function () {
  $breadcrumb = [
    ['name' => "Documentation"]
  ];
  include BASEPATH . "/header.php";
  include BASEPATH . "/documentation.php";
  include BASEPATH . "/footer.php";
});

Route::add('/hla', function () {
  $breadcrumb = [
    ['name' => "HLA Typing Browser"]
  ];
  include BASEPATH . "/php/_db.php";
  include BASEPATH . "/php/_config.php";
  include BASEPATH . "/header.php";
  include BASEPATH . "/hla.php";
  include BASEPATH . "/footer.php";
});


Route::add('/rna/(ll-100|breast-cancer|prostate-cancer|all)', function ($project) {
  include BASEPATH . "/php/_db.php";
  include BASEPATH . "/php/_config.php";

  $breadcrumb = [
    // ['name' => "RNA-seq", 'path' => '/rna'],
    ['name' => project_name($project) . ' RNA-seq']
  ];

  include BASEPATH . "/header.php";
  include BASEPATH . "/ngs_$project.php";
  include BASEPATH . "/footer.php";
});

Route::add('/rna/(ll-100|breast-cancer|prostate-cancer|all)/(heat|bar)', function ($project, $plottype) {
  include BASEPATH . "/php/_db.php";
  include BASEPATH . "/php/_config.php";

  $breadcrumb = [
    // ['name' => "RNA-seq", 'path' => '/rna'],
    ['name' => project_name($project) . ' RNA-seq', 'path' => '/rna/' . $project],
    ['name' => ($plottype == 'heat' ? 'Heat map' : 'Bar chart')]
  ];

  include BASEPATH . "/header.php";
  include BASEPATH . "/app.php";
  include BASEPATH . "/footer.php";
});

Route::add('/genes', function () {
  include BASEPATH . "/php/_db.php";
  $breadcrumb = [
    ['name' => "Genes"]
  ];
  include BASEPATH . "/php/_config.php";
  include BASEPATH . "/header.php";
  include BASEPATH . "/genes.php";
  include BASEPATH . "/footer.php";
});

Route::add('/celllines', function () {
  include BASEPATH . "/php/_db.php";
  $breadcrumb = [
    ['name' => "Cell lines"]
  ];
  include BASEPATH . "/php/_config.php";
  include BASEPATH . "/header.php";
  include BASEPATH . "/celllines.php";
  include BASEPATH . "/footer.php";
});

Route::add('/cellline/(.*)', function ($cellline) {
  include BASEPATH . "/php/_db.php";
  $breadcrumb = [
    ['name' => "Cell lines",  'path' => '/celllines'],
    ['name' => $cellline]
  ];

  include BASEPATH . "/php/Parsedown.php";
  include BASEPATH . "/php/_config.php";
  include BASEPATH . "/header.php";
  include BASEPATH . "/cell.php";
  include BASEPATH . "/footer.php";
});

Route::add('/(str)', function ($project) {
  $breadcrumb = [
    ['name' => "Short Tandem Repeats (human)"]
  ];
  $pagename = 'overview';
  include BASEPATH . "/header.php";
  // var_dump($_POST);
  include BASEPATH . "/str.php";
  include BASEPATH . "/footer.php";
});

Route::add('/(str)/search', function ($project) {
  $breadcrumb = [
    ['name' => "Short Tandem Repeats (human)", 'path' => '/str'],
    ['name' => "STR Profile Search"]
  ];
  $pagename = 'search';
  include BASEPATH . "/header.php";
  include BASEPATH . "/str_search.php";
  include BASEPATH . "/footer.php";
});

Route::add('/(str)/search', function ($project) {
  $breadcrumb = [
    ['name' => "Short Tandem Repeats (human)", 'path' => '/str'],
    ['name' => "STR Profile Search"]
  ];
  $pagename = 'search';
  include BASEPATH . "/php/_db.php";
  include BASEPATH . "/header.php";
  // var_dump($_POST);
  include BASEPATH . "/str_result.php";
  include BASEPATH . "/footer.php";
}, 'post');

Route::add('/(str)/excel', function () {
  include BASEPATH . "/php/str_excel.php";
}, 'post');

Route::add('/(str)/browse', function ($project) {
  $breadcrumb = [
    ['name' => "Short Tandem Repeats (human)", 'path' => '/str'],
    ['name' => "STR Profile Browser"]
  ];
  $pagename = 'browse';
  include BASEPATH . "/php/_db.php";
  include BASEPATH . "/php/_config.php";
  include BASEPATH . "/header.php";
  include BASEPATH . "/str_browse.php";
  include BASEPATH . "/footer.php";
});


Route::add('/(str)/view/(\d+)', function ($project, $str_id) {
  $breadcrumb = [
    ['name' => "Short Tandem Repeats (human)", 'path' => '/str'],
    ['name' => "STR Browser", 'path' => '/str/browse'],
    ['name' => "Profile Viewer"]
  ];
  include BASEPATH . "/php/_db.php";

  $stmt = $db->prepare("SELECT cell_id,reference,ACC,cellline,date,gender,EBV,largeT,MMR FROM str_meta WHERE str_id = ?");
  $stmt->execute([$str_id]);
  $meta = $stmt->fetch(PDO::FETCH_ASSOC);
  if (empty($meta)) {
    header("Location: " . ROOTPATH . "/str/browse?msg=profile-does-not-exist");
  }
  include BASEPATH . "/header.php";
  include BASEPATH . "/str_viewer.php";
  include BASEPATH . "/footer.php";
});


Route::add('/(coi)', function ($project) {
  $breadcrumb = [
    ['name' => "COI DNA Barcoding (animal)"]
  ];
  $pagename = 'overview';
  include BASEPATH . "/header.php";
  // var_dump($_POST);
  include BASEPATH . "/coi.php";
  include BASEPATH . "/footer.php";
});

Route::add('/(coi)/browse', function ($project) {
  $breadcrumb = [
    ['name' => "COI DNA Barcoding (animal)", 'path' => '/coi'],
    ['name' => "COI DNA Browser"]
  ];
  $pagename = 'browse';
  include BASEPATH . "/php/_db.php";
  include BASEPATH . "/php/_config.php";
  include BASEPATH . "/header.php";
  include BASEPATH . "/coi_browse.php";
  include BASEPATH . "/footer.php";
});

Route::add('/(coi)/view/(\d+)', function ($project, $cell_id) {

  include BASEPATH . "/php/_db.php";
  $stmt = $db->prepare("SELECT * FROM coi LEFT JOIN celllines USING (cell_id) WHERE cell_id = ?");
  $stmt->execute([$cell_id]);
  $coi = $stmt->fetch(PDO::FETCH_ASSOC);
  if (empty($coi)) {
    header("Location: " . ROOTPATH . "/coi/browse?msg=report-does-not-exist");
  }

  $breadcrumb = [
    ['name' => "COI DNA Barcoding (animal)", 'path' => '/coi'],
    ['name' => "COI DNA Browser", 'path' => '/coi/browse'],
    ['name' => "Species Report for " . $coi['cellline']]
  ];


  include BASEPATH . "/header.php";
  include BASEPATH . "/coi_viewer.php";
  include BASEPATH . "/footer.php";
});

Route::add('/(coi)/pdf/(\d+)', function ($project, $cell_id) {

  include BASEPATH . "/php/_db.php";
  $stmt = $db->prepare("SELECT * FROM coi LEFT JOIN celllines USING (cell_id) WHERE cell_id = ?");
  $stmt->execute([$cell_id]);
  $coi = $stmt->fetch(PDO::FETCH_ASSOC);
  if (empty($coi)) {
    header("Location: " . ROOTPATH . "/coi/browse?msg=report-does-not-exist");
  }


  require_once BASEPATH . "/php/Parsedown.php";
  require_once BASEPATH . "/php/pdf_writer.php";
  $parsedown = new Parsedown();
  $html = $parsedown->text($coi['markdown']);
  $pdf = new PDF();
  $pdf->AddPage();

  $title = 'COI Species Report ' . $coi['cellline'];
  $pdf->SetMargins(23, 30);
  $pdf->SetTitle($title);
  $pdf->SetAuthor('DSMZCellDive');

  $pdf->WriteHTML($html);


  $pdf->AliasNbPages();
  $pdf->Output();
});



if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {

  Route::add('/user/logout', function () {
    unset($_SESSION["username"]);
    $_SESSION['loggedin'] = false;
    header("Location: " . ROOTPATH . "/");
  }, "get");

  Route::add('/(str)/edit/(\d+)', function ($project, $str_id) {
    $breadcrumb = [
      ['name' => "Short Tandem Repeats (human)", 'path' => '/str'],
      ['name' => "STR Browser", 'path' => '/str/browse'],
      ['name' => "Profile Editor"]
    ];
    include BASEPATH . "/php/_db.php";

    $stmt = $db->prepare("SELECT str_id,cell_id,created,reference,ACC,cellline,lot,lot_2,`date`,date_2,date_animal_pcr,M,R,CH,SH,notes,gender,EBV,largeT,MMR,highlight FROM str_meta WHERE str_id = ?");
    $stmt->execute([$str_id]);
    $meta = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($meta)) {
      header("Location: " . ROOTPATH . "/str/browse?msg=profile-does-not-exist");
    }
    include BASEPATH . "/header.php";
    include BASEPATH . "/str_editor.php";
    include BASEPATH . "/footer.php";
  });


  Route::add('/(str)/(edit-meta|edit-profile|delete|add)/(\d+)', function ($project, $action, $str_id) {
    $breadcrumb = [
      ['name' => "Short Tandem Repeats (human)", 'path' => '/str'],
      ['name' => "STR Profile Editor"]
    ];
    include BASEPATH . "/php/_db.php";


    include BASEPATH . "/php/str_crud.php";
  }, 'post');
} else {

  Route::add('/user/login', function () {
    $page = "userlogin";

    include BASEPATH . "/php/_login.php";
    $ip = getIPAddress();
    if ($_SERVER['SERVER_NAME'] != 'testserver' && substr($ip, 0, 10) !== "172.18.242") {
      // $_SERVER['REMOTE_ADDR']
      header("Location: " . ROOTPATH . "/?msg=no-access");
    } else {
      include BASEPATH . "/header.php";
      include BASEPATH . "/userlogin.php";
      include BASEPATH . "/footer.php";
    }
  }, "get");

  Route::add('/user/login', function () {
    $page = "userlogin";
    include BASEPATH . "/php/_login.php";
    // include BASEPATH . "/php/_login.php";
    if (verifyUser() === true) {
      header("Location: " . ROOTPATH . "/?welcome");
    } else {
      include BASEPATH . "/header.php";
      include BASEPATH . "/userlogin.php";
      echo 'Username unknown or password wrong.';
      include BASEPATH . "/footer.php";
    }
  }, "post");
}


Route::add('/error/([0-9]*)', function ($error) {
  // header("HTTP/1.0 $error");
  http_response_code($error);
  include BASEPATH . "/header.php";
  echo "Error: $error.";
  include BASEPATH . "/footer.php";
});

// Add a 404 not found route
Route::pathNotFound(function ($path) {
  // Do not forget to send a status header back to the client
  // The router will not send any headers by default
  // So you will have the full flexibility to handle this case
  // header('HTTP/1.0 404 Not Found');
  http_response_code(404);
  $error = 404;
  // header('HTTP/1.0 404 Not Found');
  include BASEPATH . "/header.php";
  // $browser = $_SERVER['HTTP_USER_AGENT'];
  // var_dump($browser);
  include BASEPATH . "/error.php";
  include BASEPATH . "/footer.php";
});

// Add a 405 method not allowed route
Route::methodNotAllowed(function ($path, $method) {
  // Do not forget to send a status header back to the client
  // The router will not send any headers by default
  // So you will have the full flexibility to handle this case
  header('HTTP/1.0 405 Method Not Allowed');
  $error = 405;
  include BASEPATH . "/header.php";
  echo "Error: 405 - Method not allowed.";
  include BASEPATH . "/footer.php";
});


Route::run(ROOTPATH);
