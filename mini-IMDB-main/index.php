
<?php  
    require_once 'include/dbConnect.php';
    require_once 'include/functions_quiz.php'; 
    ?>  
<!--
    COMP306-
-->
<html>

<title> Mini IMDB </title>
    
<head>
    
    <link rel="stylesheet" href="CSS_folder/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    
    <div class="topnav">
        <a class="active" href=/index.php>Mini-IMDB</a>
        <form class="example" action="result.php" method='post'>
            <select name="search_type">
                <option value="movie">Movie</option>
                <option value="tv">TV Series</option>
                <option value="actor">Actor</option>
            </select>
            <input type="text" placeholder="Search.." name="search_term">
            <button name="search" type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>
    

    <header>
    <h1>Top 10 Newest Popular Movies</h1>
    </header>
    <?php  

    $result1 = popular_movies($conn);
    $count1 = 1;
    foreach($result1 as $row){
        echo $count1;
        echo "- ";
        echo "<tr>";
        echo "<td>" . $row['name'] . "</td>";
        echo " (";
        echo "<td>" . $row['rating'] . "</td>";
        echo ") ";
        echo "</tr>";
        $count1 += 1;
    }

    echo "</table>"; 

    ?>
    <header>
    <h1>Top 10 Highest Rated Movies</h1>
    </header>
    <?php  

    $pop_mov = rated_movies($conn);

    $count2 = 1;
    foreach($pop_mov as $row){
        echo $count2;
        echo "- ";
        echo "<tr>";
        echo "<td>" . $row['Movie_name'] . "</td>";
        echo " ";
        echo "</tr>";
        $count2 += 1;
    }

    ?>
    <header>
    <h1>Top 10 Highest Rated TV Series</h1>
    </header>
    <?php  

    $pop_tv = rated_tvseries($conn);

    $count3 = 1;
    foreach($pop_tv as $row){
        echo $count3;
        echo "- ";
        echo "<tr>";
        echo "<td>" . $row['TV_name'] . "</td>";
        echo " ";
        echo "</tr>";
        $count3 += 1;
    }
    ?>
        <h1>Highest Rated Movies of 21st Century by Year</h1>
     <?php  
         $top_rated_movie_by_year = top_rated_movies_by_year($conn);
         echo "<table border='1'>";
         echo "<tr>";
         echo "<th>Name</th>";
         echo "<th>Rating</th>";
         echo "<th>Year</th>";
         echo "</tr>";
         foreach($top_rated_movie_by_year as $row){
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['rating'] . "</td>";
            echo "<td>" . $row['release_date'] . "</td>";
            echo "</tr>";
         }echo "</table>";
         ?>
         <h1>Highest Rated TV Series of 21st Century by Year</h1>
      <?php  
          $top_rated_tv_series_by_year = top_rated_tv_series_by_year($conn);
          echo "<table border='1'>";
          echo "<tr>";
          echo "<th>Name</th>";
          echo "<th>Rating</th>";
          echo "<th>Year</th>";
          echo "</tr>";
          foreach($top_rated_tv_series_by_year as $row){
             echo "<tr>";
             echo "<td>" . $row['name'] . "</td>";
             echo "<td>" . $row['rating'] . "</td>";
             echo "<td>" . $row['release_date'] . "</td>";
             echo "</tr>";
          }echo "</table>";
          ?>
          <h1>Most Popular Movie Actors of the Last 2 Years</h1>
       <?php  
           $most_popular_actors = most_popular_actors_of_last_2_years($conn);
           echo "<table border='1'>";
           echo "<tr>";
           echo "<th>Actor Name</th>";
           echo "<th>Total number of votes they have for their movies</th>";
           echo "</tr>";
           foreach($most_popular_actors as $row){
              echo "<tr>";
              echo "<td>" . $row['name'] . "</td>";
              echo "<td>" . $row['sum(num_of_votes)'] . "</td>";
              echo "</tr>";
           }echo "</table>";


    $mov_genre = genre_movie($conn);

    foreach ($mov_genre as $row_gen) {

        $gen_mov = $row_gen['genre'];
        $popmov_genre = popmov_genre($conn, $gen_mov); 

        $count4 = 1;
        foreach($popmov_genre as $row){
            if (is_null($row['name']) !== true and $count4 == 1) {
                ?>
                <header>
                <h1>Top 10 Highest Rated Movies in <?php echo $gen_mov?> </h1>
                </header>
                <?php 
            }
            echo $count4;
            echo "- ";
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo " ";
            echo "</tr>";
            $count4 += 1;
        }
        
    }

    $tv_genre = genre_tv($conn);

    foreach ($tv_genre as $row_gen) {

        $gen_tv = $row_gen['genre'];
        $poptv_genre = poptv_genre($conn, $gen_tv);

        $count5 = 1;
        foreach($poptv_genre as $row){
            if (is_null($row['name']) !== true and $count5 == 1) {
                ?>
                <header>
                <h1>Top 10 Highest Rated TV Series in <?php echo $gen_tv?> </h1>
                </header>
                <?php 
            }
            echo $count5;
            echo "- ";
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo " ";
            echo "</tr>";
            $count5 += 1;
        }
        
    }

    ?> 

</body>
</html>
