 <?php
#ABDULLAH - ACTOR:
function check_actorname($conn, $actorname){
    return is_contains($conn, $actorname, "name", "ACTOR");
}
function check_howmany_actor($conn, $actorname){
    $query = "SELECT ACTOR.birth_date FROM mini_imdb.ACTOR AS ACTOR WHERE ACTOR.name='$actorname';";
    
    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }
}
function get_general_info_actor($conn, $actorname, $actordate) {

    $query = "SELECT ACTOR.name, ACTOR.birth_date, ACTOR.death_date FROM mini_imdb.ACTOR WHERE ACTOR.name='$actorname' AND ACTOR.birth_date=$actordate;";

    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }   
}

function get_series_info($conn, $actorname, $actordate) {

    $query = "SELECT DISTINCT TV.name, TV.release_date, TV.rating, TV.num_of_episodes FROM mini_imdb.PLAYS_IN_SERIES AS P, mini_imdb.TV_SERIES AS TV, mini_imdb.ACTOR AS A  WHERE   P.series_id=TV.series_id AND P.actor_id=A.actor_id AND A.name='$actorname' AND A.birth_date=$actordate;";

    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }

}
function get_movies_info($conn, $actorname, $actordate) {

    $query = "SELECT MOV.name,  MOV.release_date, MOV.rating, MOV.duration FROM mini_imdb.PLAYS_IN_MOVIES AS P, mini_imdb.MOVIE AS MOV, mini_imdb.ACTOR AS A  WHERE P.film_id=MOV.film_id AND P.actor_id=A.actor_id AND A.name='$actorname' AND A.birth_date=$actordate;";

    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }

}
#Abdullah complex
function number_of_partnership($conn,$actorname, $actordate){
    $query = "SELECT A1.`name` ,COUNT(*) as count
    FROM `mini_imdb`.`ACTOR` AS A1,`mini_imdb`.`ACTOR` AS A2,`mini_imdb`.`PLAYS_IN_MOVIES` AS M1, `mini_imdb`.`PLAYS_IN_MOVIES` AS M2 WHERE A1.`actor_id`=M1.actor_id and M1.film_id =M2.film_id and m2.actor_id=a2.actor_id and a2.name ='$actorname' and a1.name != '$actorname' GROUP BY A1.name having count>1 order by count desc;";
    if ($result = mysqli_query($conn, $query)) {
    return $result;
    }
}


#ABDULLAH - ACTOR ENDS#
#DİLARA - TV SERIES:
function check_tvname($conn, $tvname){
    return is_contains($conn, $tvname, "name", "TV_SERIES");
}

function check_howmany($conn, $tvname){
    $query = "SELECT TV.release_date FROM mini_imdb.TV_SERIES AS TV WHERE TV.name='$tvname';";
    
    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }
}

function get_general_info($conn, $tvname, $tvdate) {

    $query = "SELECT TV.release_date, TV.rating, TV.num_of_episodes, G.genre AS genre FROM mini_imdb.TV_SERIES AS TV, mini_imdb.GENRE_SERIES AS G WHERE G.series_id=TV.series_id AND TV.name='$tvname' AND TV.release_date=$tvdate;";

    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }
}

function get_actor_info($conn, $tvname, $tvdate) {

    $query = "SELECT A.name FROM mini_imdb.PLAYS_IN_SERIES AS P, mini_imdb.TV_SERIES AS TV, mini_imdb.ACTOR AS A WHERE P.series_id=TV.series_id AND P.actor_id=A.actor_id AND TV.name='$tvname' AND TV.release_date=$tvdate;";

    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }

}

function get_episode_info($conn, $tvname, $tvdate) {

    $query = "SELECT E.season_no, E.no, E.title, E.duration, E.rating FROM mini_imdb.EPISODE AS E, mini_imdb.TV_SERIES AS TV WHERE E.series_id=TV.series_id AND TV.name='$tvname' AND TV.release_date=$tvdate;";

    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }
}

#Complex Query: Total number of tv series + movies each actor of the searched tv series have played

function number_of_total_roles($conn, $tvname, $tvdate) {

    $query = "SELECT A1.name, COUNT(*) AS count FROM mini_imdb.PLAYS_IN_SERIES AS P1, mini_imdb.PLAYS_IN_MOVIES AS M, mini_imdb.ACTOR AS A1 WHERE P1.actor_id=A1.actor_id AND M.actor_id=A1.actor_id AND A1.actor_id IN (SELECT A2.actor_id FROM mini_imdb.ACTOR AS A2, mini_imdb.PLAYS_IN_SERIES AS P2, mini_imdb.TV_SERIES AS TV WHERE P2.actor_id=A2.actor_id AND P2.series_id=TV.series_id AND TV.name='$tvname' AND TV.release_date=$tvdate) GROUP BY A1.actor_id ORDER BY COUNT(*) DESC;";
    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }

}

############ DİLARA TV SERIES END ############

function is_contains($conn,$string, $needle, $table_name){

    $is_contains = False;
    ########
    $result = mysqli_query($conn, "SELECT * FROM $table_name WHERE $needle='$string'");
    if (mysqli_num_rows($result) != 0) {
        $is_contains = True;
    }
    ########
    return $is_contains;
}

# KORAY'S FUNCTION

function popular_movies($conn){

    $result = Null;
    ########
    $result = mysqli_query($conn, "select * 
    from MOVIE
    where release_date = 2022
    ORDER by num_of_votes desc
    LIMIT 10");
    ########
    return $result;
}

# KORAY'S complex queries

function top_rated_movies_by_year($conn) {
    $query = "SELECT name, rating, release_date
    from MOVIE
    where (release_date, rating) in
        (SELECT release_date, max(rating)
        FROM MOVIE
        GROUP BY release_date) and release_date >= 2000
    order by release_date desc;";
    
    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }

}

function most_popular_actors_of_last_2_years($conn) {
    $query = "SELECT ACTOR.name, sum(num_of_votes)
    from MOVIE, PLAYS_IN_MOVIES, ACTOR
    where PLAYS_IN_MOVIES.actor_id = ACTOR.actor_id and 
          MOVIE.film_id = PLAYS_IN_MOVIES.film_id and MOVIE.release_date>2020
    group by ACTOR.name
    order by sum(num_of_votes) desc
    limit 10";
    
    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }

}

function top_rated_tv_series_by_year($conn) {
    $query = "SELECT name, rating, release_date
    from TV_SERIES
    where (release_date, rating) in
        (SELECT release_date, max(rating)
        FROM TV_SERIES
        GROUP BY release_date) and release_date >= 2000
    order by release_date desc";
    
    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }

}

# DİLARA'S POPULAR FUNCTIONS

function rated_movies($conn) {
    $query = "SELECT M.name AS Movie_name, M.rating AS Movie_rating FROM mini_imdb.MOVIE AS M ORDER BY M.rating DESC LIMIT 10;";
    
    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }

}

function rated_tvseries($conn) {
    $query = "SELECT TV.name AS TV_name, TV.rating AS TV_rating FROM mini_imdb.TV_SERIES AS TV ORDER BY TV.rating DESC LIMIT 10;";
    
    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }

}

# DİLARA'S GENRE FUNCTIONS

function genre_movie($conn) {
    $query = "SELECT distinct G.genre FROM mini_imdb.GENRE_MOVIES AS G;";
    
    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }

}

function popmov_genre($conn, $mov_genre) {
    $query = "SELECT * FROM mini_imdb.GENRE_MOVIES AS G, mini_imdb.MOVIE AS M WHERE G.film_id=M.film_id AND G.genre='$mov_genre' ORDER BY M.rating LIMIT 10;";
    
    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }

}

function genre_tv($conn) {
    $query = "SELECT distinct G.genre FROM mini_imdb.GENRE_SERIES AS G;";
    
    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }

}

function poptv_genre($conn, $tv_genre) {
    $query = "SELECT * FROM mini_imdb.GENRE_SERIES AS G, mini_imdb.TV_SERIES AS TV WHERE G.series_id=TV.series_id AND G.genre='$tv_genre' ORDER BY TV.rating DESC LIMIT 10;";
    
    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }

}

###### DİLARA END #####


###### TUTKU - MOVIES ######

function check_moviename($conn, $moviename){
    return is_contains($conn, $moviename, "name", "MOVIE");
}

function check_howmany_movies($conn, $moviename){
    $query = "SELECT MOVIE.release_date FROM mini_imdb.MOVIE AS MOVIE WHERE MOVIE.name='$moviename';";
    
    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }
}

function get_actor_info_movie($conn, $moviename, $moviedate) {

    $query = "SELECT A.name, A.birth_date, A.death_date FROM mini_imdb.PLAYS_IN_MOVIES AS P, mini_imdb.MOVIE AS MOVIE, mini_imdb.ACTOR AS A WHERE P.film_id=MOVIE.film_id AND P.actor_id=A.actor_id AND MOVIE.name='$moviename' AND MOVIE.release_date='$moviedate';";

    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }

}

function get_movie_info($conn, $moviename, $moviedate) {

    $query = "SELECT MOVIE.name, MOVIE.release_date, MOVIE.rating, MOVIE.duration, G.genre AS genre FROM mini_imdb.GENRE_MOVIES AS G, mini_imdb.MOVIE AS MOVIE WHERE MOVIE.name='$moviename' AND MOVIE.release_date='$moviedate' AND G.film_id=MOVIE.film_id;";

    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }
}

function movies_average_duration($conn){

    $query= "SELECT AVG(MOVIE.duration) FROM mini_imdb.MOVIE AS MOVIE;";

    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }
}

function movie_duration($conn, $moviename){

    $query= "SELECT MOVIE.duration FROM mini_imdb.MOVIE AS MOVIE WHERE MOVIE.name='$moviename';";

    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }
}

function get_director_info_movie($conn, $moviename, $moviedate) {

    $query = "SELECT D.name, D.birth_date, D.death_date FROM mini_imdb.MOVIE AS MOVIE, mini_imdb.DIRECTOR AS D WHERE MOVIE.director_id=D.director_id AND MOVIE.name='$moviename' AND MOVIE.release_date='$moviedate';";

    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }

}

function movie_complex($conn, $moviename, $moviedate){

    $query = "SELECT M.name, M.rating FROM mini_imdb.MOVIE AS M WHERE M.duration IN (SELECT M.duration FROM mini_imdb.MOVIE AS M, mini_imdb.DIRECTOR AS D WHERE M.name='$moviename' AND M.release_date='$moviedate' AND M.director_id=D.director_id) ORDER BY M.rating DESC;";

    if ($result = mysqli_query($conn, $query)) {
        return $result;
    }
}

###### TUTKU END ######



function print_table($table_name, $result){

    if ($table_name === 'city'){

        ?><br>

        <table border='1'>

        <tr>

        <th>ID</th>

        <th>Name</th>

        <th>Country Code</th>

        <th>District</th>

        <th>Population</th>

        </tr>

        <?php


        foreach($result as $row){

            echo "<tr>";

            echo "<td>" . $row['ID'] . "</td>";

            echo "<td>" . $row['Name'] . "</td>";

            echo "<td>" . $row['CountryCode'] . "</td>";

            echo "<td>" . $row['District'] . "</td>";

            echo "<td>" . $row['Population'] . "</td>";

            echo "</tr>";
        }

        echo "</table>";
    }

}
