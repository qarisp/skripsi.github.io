<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Shelf Help</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

  <?php
  require_once('../sparqllib.php');
  $newRelease = sparql_get(
    'http://18.141.212.249:3030/BooksOfQaris',
    'PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
      PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
      PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
      PREFIX d:<http://www.semanticweb.org/msi-gf63/ontologies/2024/1/untitled-ontology-12#>
      
      SELECT ?label ?judul ?harga ?tahun ?rating ?penulis
      WHERE {
        {
          SELECT ?individual ?label
          WHERE {
            ?individual rdf:type/rdfs:subClassOf* d:Buku .
            ?individual rdfs:label ?label .
          }
          ORDER BY DESC(xsd:integer(REPLACE(?label, "buku", "")))
          LIMIT 5
        }
        OPTIONAL {
          ?individual d:judul ?judul;
                      d:harga ?harga;
                      d:tahunRilis ?tahun;
                      d:rating ?rating;
                d:ditulis_oleh ?pnls.
              ?pnls   d:namaPenulis ?penulis.
        }
      }
      '
  );
  $topRated = sparql_get(
    'http://18.141.212.249:3030/BooksOfQaris',
    'PREFIX d:<http://www.semanticweb.org/msi-gf63/ontologies/2024/1/untitled-ontology-12#>

      SELECT ?judul ?harga ?rating ?penulis
      WHERE {
        ?jdl d:judul ?judul;
            d:harga ?harga;
              d:rating ?rating;
              d:ditulis_oleh ?pnls.
          ?pnls d:namaPenulis ?penulis.
      }
      ORDER BY DESC (?rating)
      LIMIT 5'
  );
  $genre = sparql_get(
    'http://18.141.212.249:3030/BooksOfQaris',
    'PREFIX d:<http://www.semanticweb.org/msi-gf63/ontologies/2024/1/untitled-ontology-12#>

      SELECT ?genre
      WHERE {
        ?gnr d:namaGenre ?genre
      } ORDER BY ASC (?genre)
      LIMIT 21'
  )
  ?>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">Shelf Help</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="GET" action="searchResult.php">
        <input type="text" name="query" id="query" placeholder="Cari Judul atau Penulis" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div>


  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="index.php">
          <i class="bi bi-grid"></i>
          <span>Home</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Browse</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="allGenres.php">
              <i class="bi bi-circle"></i><span>All Genres</span>
            </a>
          </li>
          <li>
            <a href="newReleases.php">
              <i class="bi bi-circle"></i><span>New Releases</span>
            </a>
          </li>
          <li>
            <a href="topRated.php">
              <i class="bi bi-circle"></i><span>Top Rated</span>
            </a>
          </li>
        </ul>
      </li><!-- End Browse Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="recommendation.php">
          <i class="bi bi-grid"></i>
          <span>Recommendation</span>
        </a>
      </li><!-- End Recommendation Nav -->

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Home</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">

            <!-- New Releases -->
            <div class="col-12">
              <div class="card two-column overflow-auto">

                <div class="card-body pb-0">
                  <h5 class="card-title">New Releases</h5>

                  <table class="table table-borderless table-fixed">
                    <thead>
                      <tr>
                        <th scope="col"> </th>
                        <th scope="col">Judul</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Penulis</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $i = 0; ?>
                      <?php foreach ($newRelease as $dat) : ?>
                        <tr>
                          <th scope="row">
                            <a href="bookPage.php?title=<?= urlencode($dat['judul']) ?>">
                              <?php
                              $imageFileName = "assets/img/" . str_replace([':', '?'], '_', $dat['judul']) . ".jpg";
                              if (file_exists($imageFileName)) {
                                // If the image file exists, use it
                                echo "<img src=\"$imageFileName\" alt=\"\">";
                              } else {
                                // If the image file doesn't exist, use stock.jpg
                                echo "<img src=\"assets/img/stock.jpg\" alt=\"\">";
                              }
                              ?>
                            </a>
                          </th>
                          <td><a href="bookPage.php?title=<?= urlencode($dat['judul']) ?>" class="text-primary fw-bold"><?= $dat['judul'] ?></a></td>
                          <td>Rp. <?= number_format($dat['harga'], 0, ',', ',') ?></td>
                          <td class="fw-bold"><?= $dat['rating'] ?></td>
                          <td><a href="authorPage.php?penulis=<?= urlencode($dat['penulis']) ?>"><?= $dat['penulis'] ?></a></td>
                        </tr>
                      <?php endforeach; ?>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                          <a href="newReleases.php" class="text-primary fw-bold">More...</a>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End New Releases -->

            <!-- Top Rated -->
            <div class="col-12">
              <div class="card two-column overflow-auto">

                <div class="card-body pb-0">
                  <h5 class="card-title">Top Rated</h5>

                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col"> </th>
                        <th scope="col">Judul</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Penulis</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($topRated as $dat) : ?>
                        <tr>
                          <th scope="row">
                            <a href="bookPage.php?title=<?= urlencode($dat['judul']) ?>">
                              <?php
                              $imageFileName = "assets/img/" . str_replace([':', '?'], '_', $dat['judul']) . ".jpg";
                              if (file_exists($imageFileName)) {
                                // If the image file exists, use it
                                echo "<img src=\"$imageFileName\" alt=\"\">";
                              } else {
                                // If the image file doesn't exist, use stock.jpg
                                echo "<img src=\"assets/img/stock.jpg\" alt=\"\">";
                              }
                              ?>
                            </a>
                          </th>
                          <td><a href="bookPage.php?title=<?= urlencode($dat['judul']) ?>" class="text-primary fw-bold"><?= $dat['judul'] ?></a></td>
                          <!-- <a href="genre.php?genre=<?= urlencode("Art") ?>">Art</a> -->
                          <td>Rp. <?= number_format($dat['harga'], 0, ',', ',') ?></td>
                          <td class="fw-bold"><?= $dat['rating'] ?></td>
                          <td><a href="authorPage.php?penulis=<?= urlencode($dat['penulis']) ?>"><?= $dat['penulis'] ?></a></td>
                        </tr>
                      <?php endforeach; ?>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                          <a href="topRated.php" class="text-primary fw-bold">More...</a>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Top Rated -->

          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">

          <!-- News & Updates Traffic -->
          <div class="card">
            <div class="card-body pb-0">
              <h5 class="card-title">Genres</h5>

              <div class="news">
                <!-- <?php foreach ($genre as $dat) : ?>
                  <div class="post-item clearfix">
                    <h4><a href="genre.php?genre=<?= urlencode($dat['genre']) ?>"><?= $dat['genre'] ?></a></h4>
                  </div>
                <?php endforeach; ?> -->

                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Art") ?>">Art</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Adventure") ?>">Adventure</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Biography") ?>">Biography</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Business") ?>">Business</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Childrens") ?>">Childrens</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Christian") ?>">Christian</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Classics") ?>">Classics</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Comics") ?>">Comics</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Contemporary") ?>">Contemporary</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Cooking") ?>">Cooking</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Crime") ?>">Crime</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Dystopia") ?>">Dystopia</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Entrepreneurship") ?>">Entrepreneurship</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Fantasy") ?>">Fantasy</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Fiction") ?>">Fiction</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("History") ?>">History</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Mystery") ?>">Mystery</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Nonfiction") ?>">Nonfiction</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Philosophy") ?>">Philosophy</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Psychology") ?>">Psychology</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Romance") ?>">Romance</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Science Fiction") ?>">Science Fiction</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Self Help") ?>">Self Help</a></h4>
                </div>
                <div class="post-item clearfix">
                  <h4><a href="genre.php?genre=<?= urlencode("Thriller") ?>">Thriller</a></h4>
                </div>

              </div><!-- End sidebar recent posts-->

            </div>
          </div><!-- End News & Updates -->

        </div><!-- End Right side columns -->

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Shelf Help</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      Designed by <a href="https://github.com/qarisp">Qaris</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>