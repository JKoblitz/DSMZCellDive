<?php

session_start();

define('ROOTPATH', '');
define('INSIGHT', true);
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
  $cellline = urldecode($cellline);
  $breadcrumb = [
    ['name' => "Cell lines",  'path' => '/celllines'],
    ['name' => $cellline]
  ];

  if (substr($cellline, 0, 4) === "ACC-" && is_numeric(str_replace('ACC-', '', $cellline))) {
    $stmt = $db->prepare("SELECT *
  FROM celllines WHERE cell_id = ?
  ");
    $stmt->execute([str_replace('ACC-', '', $cellline)]);
    $cell = $stmt->fetch(PDO::FETCH_ASSOC);
  } else {
    $stmt = $db->prepare("SELECT *
  FROM celllines WHERE cellline LIKE ?
  ");
    $stmt->execute([$cellline]);
    $cell = $stmt->fetch(PDO::FETCH_ASSOC);
  }

  if (empty($cell)) {
    header("Location: " . ROOTPATH . "/celllines?msg=cellline-does-not-exist");
  }

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
    ['name' => "COI mtDNA Sequence Browser"]
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
    ['name' => "COI mtDNA Sequence Browser", 'path' => '/coi/browse'],
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

    $stmt = $db->prepare("SELECT str_id,cell_id,created,reference,`source`,ACC,cellline,lot,lot_2,`date`,date_2,date_animal_pcr,M,R,CH,SH,notes,gender,EBV,largeT,MMR,highlight FROM str_meta WHERE str_id = ?");
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


  Route::add('/(str)/(import)', function ($project, $pagename) {
    $breadcrumb = [
      ['name' => "Short Tandem Repeats (human)", 'path' => '/str'],
      ['name' => "STR", 'path' => '/str/browse'],
      ['name' => "Import profiles"]
    ];
    include BASEPATH . "/header.php";
    include BASEPATH . "/str_upload.php";
    include BASEPATH . "/footer.php";
  });

  Route::add('/(str)/(import)', function ($project, $pagename) {
    $breadcrumb = [
      ['name' => "Short Tandem Repeats (human)", 'path' => '/str'],
      ['name' => "STR", 'path' => '/str/browse'],
      ['name' => "Import profiles"]
    ];
    include BASEPATH . "/php/_db.php";

    $stmt = $db->query("SELECT MAX(update_id) FROM str_meta");
    $update_id = $stmt->fetch(PDO::FETCH_COLUMN);
    $update_id++;

    include BASEPATH . "/header.php";
    include BASEPATH . "/str_upload.php";
    include BASEPATH . "/footer.php";
  }, 'post');

  Route::add('/(str)/import-confirm', function ($project) {
    $breadcrumb = [
      ['name' => "Short Tandem Repeats (human)", 'path' => '/str'],
      ['name' => "STR", 'path' => '/str/browse'],
      ['name' => "Import profiles"]
    ];
    include BASEPATH . "/php/_db.php";
    include_once BASEPATH . "/php/str_file.php";

    $update_id = $_POST['update_id'];
    $targetPath = BASEPATH . '/uploads/upload_' . $update_id . '.csv';
    $result = read_str($targetPath);

    foreach ($result['data'] as $data) {
      $meta = $data['meta'];
      $profile = $data['profile'];

      $columns = array();
      $values = array();
      foreach ($meta as $key => $value) {
        $columns[] = "`$key`";
        $values[] = $value;
      }
      $sql = "INSERT INTO str_meta 
                    (`update_id`, " . implode(',', $columns) . ") 
                    VALUES ('$update_id', " . implode(',', array_fill(0, count($columns), '?')) . ")";

      $stmt = $db->prepare($sql);
      $stmt->execute($values);
      $str_id = $db->lastInsertId();

      foreach ($profile as $p) {
        $locus = $p[0];
        $allele = $p[1];
        $value = $p[2];
        // var_dump([$str_id, $locus, $allele, $value, $value]);
        $stmt = $db->prepare(
          "INSERT INTO str_profile 
          (str_id, locus, allele, `value`, `value_str`) 
          VALUES (?,?,?,?,?)"
        );
        $stmt->execute([$str_id, $locus, $allele, $value, $value]);
      }
    }
    header("Location: " . ROOTPATH . "/str/browse?upload=$update_id");
  }, 'post');
} else {

  Route::add('/user/login', function () {
    // omitted for official repository
  }, "get");

  Route::add('/user/login', function () {
    // omitted for official repository
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
  header('HTTP/1.0 404 Not Found');
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
  header('HTTP/1.0 405 Method Not Allowed');
  $error = 405;
  include BASEPATH . "/header.php";
  echo "Error: 405 - Method not allowed.";
  include BASEPATH . "/footer.php";
});


Route::run(ROOTPATH);
