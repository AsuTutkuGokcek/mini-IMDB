<?php

require_once 'include/dbConnect.php';
require_once 'include/functions_quiz.php';


if (isset($_POST['search'])){
    
    $search_type = $_POST['search_type'];
    $search_term = $_POST['search_term'];

    if ($search_type == 'actor'){
        # ACTOR YAPAN KOD BURAYA
        $actorname = $search_term;
        if (check_actorname($conn, $actorname) !== true) {
            printf("Searched Actor %s", $search_term);
            ?><br>
            <?php
            exit("Not a actor name!");
        }
        $actcount = check_howmany_actor($conn, $actorname);

        foreach ($actcount as $count){
            ?> 
            <h1>ACTOR: <?php echo $actorname ?> </h1>
            <?php
            $actordate=$count['birth_date'];

            $actgeneral = get_general_info_actor($conn, $actorname, $actordate);
            $actorseries = get_series_info($conn, $actorname, $actordate);
            $actormovies = get_movies_info($conn, $actorname, $actordate);
            $actorcomplex = number_of_partnership($conn,$actorname, $actordate);
            if (mysqli_num_rows($actgeneral)!=0){
                ?><br>
                <table border='1'>
                <tr>
                <th>Name</th>
                <th>Birth Date</th>
                <th>Death Date</th>
                </tr>
                <?php
                $count = 1;
                echo "<tr>";
                foreach($actgeneral as $row){
                    if ($count == 1) {
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['birth_date'] . "</td>";
                        echo "<td>" . $row['death_date'] . "</td>"; 
                    }
                    
                    echo " ";
                    $count += 1;
                }
                
                echo "</tr>";

                echo "</table>";

            }
            ?> 
            <h2>Tv Series:</h2>
            <?php
                        if (mysqli_num_rows($actorseries)!=0){
                            ?>
                            <table border='1'>
                            <tr>
                            <th>Name</th> 
                            <th>Release Date</th>
                            <th>Rating</th>
                            <th>Number Of Episode</th>
                            
                            </tr>
                            <?php
                            
                            foreach($actorseries as $row){
                                

                                    echo "<tr>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>" . $row['release_date'] . "</td>";
                                    echo "<td>" . $row['rating'] . "</td>";
                                    echo "<td>" . $row['num_of_episodes'] . "</td>";
                         
                                    echo "</tr>";
                                
                               
                            }
                            echo "</table>";
                        }
                        ?> 
                        <h2>Movies:</h2>
                        <?php

                                    if (mysqli_num_rows($actormovies)!=0){
                                        ?>
                                        <table border='1'>
                                        <tr>
                                        <th>Name</th> 
                                        <th>Release Date</th>
                                        <th>Rating</th>
                                        <th>Duration (min)</th>
                                        
                                        </tr>
                                        <?php
                                        
                                        foreach($actormovies as $row){
                                            
            
                                                echo "<tr>";
                                                echo "<td>" . $row['name'] . "</td>";
                                                echo "<td>" . $row['release_date'] . "</td>";
                                                echo "<td>" . $row['rating'] . "</td>";
                                                echo "<td>" . $row['duration'] . "</td>";
                                     
                                                echo "</tr>";
                                            
                                           
                                        }
                                        echo "</table>";
                                    }
                                    ?> 
            <h2>Fun Fact!</h2>
            <h3>Here are the actor's most co-stars in movies:</h3>
            <?php
            if (mysqli_num_rows($actorcomplex)!=0){
                ?>
                <table border='1'>
                <?php
                foreach($actorcomplex as $row){
        
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['count'] . "</td>";

                    echo "</tr>";
                }
                echo "</table>";
            }


        }
    }
    
    else if ($search_type == 'tv'){
        # TV SERIES YAPAN KOD BURAYA
        $tvname = $search_term;

        if (check_tvname($conn, $tvname) !== true) {
            printf("Searched TV series: %s", $search_term);
            ?><br>
            <?php
            exit("Not a valid TV series name!");
        }
        $tvcount = check_howmany($conn, $tvname);

        foreach ($tvcount as $count) {
            ?> 
            <h1>TV Series: <?php echo $tvname ?> </h1>
            <?php

            $tvdate = $count['release_date'];
        
            $tvgeneral = get_general_info($conn, $tvname, $tvdate);
            $tvactors = get_actor_info($conn, $tvname, $tvdate);
            $tvepisodes = get_episode_info($conn, $tvname, $tvdate);
            $tvcomplex = number_of_total_roles($conn, $tvname, $tvdate);
            
            if (mysqli_num_rows($tvgeneral)!=0){
                ?><br>
                <table border='1'>
                <tr>
                <th>Release Date</th>
                <th>Rating</th>
                <th>Episode Number</th>
                <th>Genre</th>
                </tr>
                <?php
                $count = 1;
                echo "<tr>";
                foreach($tvgeneral as $row){
                    if ($count == 1) {
                        echo "<td>" . $row['release_date'] . "</td>";
                        echo "<td>" . $row['rating'] . "</td>";
                        echo "<td>" . $row['num_of_episodes'] . "</td>"; 
                    }
                    echo "<td>" . $row['genre'] . "</td>";
                    echo " ";
                    $count += 1;
                }
                
                echo "</tr>";

                echo "</table>";
            }

            ?> 
            <h2>Actors:</h2>
            <?php
            
            if (mysqli_num_rows($tvactors)!=0){
                ?>
                <table border='1'>
                <?php
                foreach($tvactors as $row){
        
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";

                    echo "</tr>";
                }
                echo "</table>";
            }
            
            ?> 
            <h2>Episodes:</h2>
            <?php

            if (mysqli_num_rows($tvepisodes)!=0){
                ?>
                <table border='1'>
                <tr>
                <th>Season Number</th> 
                <th>Episode Number</th>
                <th>Episode Title</th>
                <th>Episode Duration</th>
                <th>Episode Rating</th>
                </tr>
                <?php
                foreach($tvepisodes as $row){

                    echo "<tr>";
                    echo "<td>" . $row['season_no'] . "</td>";
                    echo "<td>" . $row['no'] . "</td>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['duration'] . "</td>";
                    echo "<td>" . $row['rating'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            
            ?> 
            <h2>Fun Fact!</h2>
            <h3>Here are the total number of roles the actors have played in tv series and movies:</h3>
            <?php

            
            if (mysqli_num_rows($tvcomplex)!=0){
                ?>
                <table border='1'>
                <?php
                foreach($tvcomplex as $row){
        
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['count'] . "</td>";

                    echo "</tr>";
                }
                echo "</table>";
            }

        
        }
    
    }

    
    else if ($search_type == 'movie'){
        # MOVIE YAPAN KOD BURAYA
        $moviename = $search_term;

        if (check_moviename($conn, $moviename) !== true) {
            printf("Searched movie: %s", $search_term);
            ?><br>
            <?php
            exit("Not a valid movie name!");
        }

        $moviecount = check_howmany_movies($conn, $moviename);

        foreach($moviecount as $count){

            $moviedate = $count['release_date'];

            $movieinfo = get_movie_info($conn, $moviename, $moviedate);
            $movieactors = get_actor_info_movie($conn, $moviename, $moviedate);
            $moviedirector = get_director_info_movie($conn, $moviename, $moviedate);


                if (mysqli_num_rows($movieinfo)!=0){
                
                ?> 
                <h2>Movie: <?php echo $moviename ?> </h2>
                <?php

                    ?>
                    <table border='1'>
                    <tr>
                    <th>Movie Release Date</th>
                    <th>Movie Rating</th>
                    <th>Movie Duration</th>
                    <th>Movie Genre</th>
                    </tr>
                    <?php
                    $count = 1;
                    echo "<tr>";
                    foreach($movieinfo as $row){
                        if ($count == 1) {
                        echo "<td>" . $row['release_date'] . "</td>";
                        echo "<td>" . $row['rating'] . "</td>";
                        echo "<td>" . $row['duration'] . "</td>";
                        }
                        echo "<td>" . $row['genre'] . "</td>";
                        echo " ";
                        $count += 1;
                        }
                        echo "</tr>";
                
                echo "</table>";
                }
            
                ?> 
                <h2>Director:</h2>
                <?php
                
                if (mysqli_num_rows($moviedirector)!=0){
                    ?>
                        <table border='1'>
                        <tr>
                        <th>Director</th>
                        <th>Birth Date</th>
                        <th>Death Date</th>
                        </tr>
                        <?php
                    foreach($moviedirector as $row){
                        
                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['birth_date'] . "</td>";
                        echo "<td>" . $row['death_date'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }



            ?> 
            <h2>Actors:</h2>
            <?php
            
            if (mysqli_num_rows($movieactors)!=0){
                ?>
                    <table border='1'>
                    <tr>
                    <th>Actor</th>
                    <th>Birth Date</th>
                    <th>Death Date</th>
                    </tr>
                    <?php
                foreach($movieactors as $row){
                    
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['birth_date'] . "</td>";
                    echo "<td>" . $row['death_date'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }

            $moviecomplex = movie_complex($conn, $moviename, $moviedate);

            ?> 
            <h2>If you'd like...</h2>
            <h3>Here's the list of the movies with the same duration from best to worst ratings!</h3>
            <?php

           if (mysqli_num_rows($moviecomplex)!=0){
                ?>
                <table border='1'>
                <tr>
                    <th>Movie Name</th>
                    <th>Movie Rating</th>
                    </tr>
                <?php
                foreach($moviecomplex as $row){
        
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['rating'] . "</td>";

                    echo "</tr>";
                }
                echo "</table>";
            }
            
        
        }

    }

}


