import mysql.connector
import csv

db_connection = mysql.connector.connect(
  host="localhost",
  user="root",
  passwd="Korayt123", # change password
  auth_plugin='mysql_native_password'
)
print(db_connection)

# creating database_cursor to perform SQL operation to run queries
db_cursor = db_connection.cursor(buffered=True)

# deleting the database if it already exists
db_cursor.execute("SHOW DATABASES")
for db in db_cursor:
    if 'mini_imdb' in db:
        db_cursor.execute("DROP DATABASE mini_imdb")

# creating the database
db_cursor.execute("CREATE DATABASE mini_imdb")

    
db_cursor.execute("USE mini_imdb")

def printProgressBar (iteration, total, prefix = '', suffix = '', decimals = 1, length = 100, fill = '█', printEnd = "\r"):
    """
    Call in a loop to create terminal progress bar
    @params:
        iteration   - Required  : current iteration (Int)
        total       - Required  : total iterations (Int)
        prefix      - Optional  : prefix string (Str)
        suffix      - Optional  : suffix string (Str)
        decimals    - Optional  : positive number of decimals in percent complete (Int)
        length      - Optional  : character length of bar (Int)
        fill        - Optional  : bar fill character (Str)
        printEnd    - Optional  : end character (e.g. "\r", "\r\n") (Str)
    """
    percent = ("{0:." + str(decimals) + "f}").format(100 * (iteration / float(total)))
    filledLength = int(length * iteration // total)
    bar = fill * filledLength + '-' * (length - filledLength)
    print(f'\r{prefix} |{bar}| {percent}% {suffix}', end = printEnd)
    # Print New Line on Complete
    if iteration == total: 
        print()



def populate_table(db_connection, db_cursor, insert_query, file_path, no_attr):
    with open(file_path, mode='r') as csv_data:
        reader = csv.reader(csv_data, delimiter=';')
        csv_data_list = list(reader)
        printProgressBar(0, len(csv_data_list), prefix = 'Creating table for '+ file_path, suffix = 'Table ' + str(tno) + '/9', length = 50)
        i = 1
        for row in csv_data_list[1:]:
            row = tuple(map(lambda x: None if x == "" else x, row[0].split(',')))
            printProgressBar(i + 1, len(csv_data_list), prefix = 'Creating table for '+ file_path, suffix = 'Table ' + str(tno) + '/9', length = 50)
            if no_attr == len(row):
                db_cursor.execute(insert_query, row)
            i+=1
    print('Creating table finished for ' + file_path)
    db_connection.commit()

tno = 1

# create DIRECTOR table
db_cursor.execute("""CREATE TABLE DIRECTOR (director_id VARCHAR(50) NOT NULL, 
                                          name VARCHAR(100) NOT NULL, 
                                          birth_date INT, 
                                          death_date INT,
                                          PRIMARY KEY(director_id))""")

insert_director = (
    "INSERT INTO DIRECTOR(director_id, name, birth_date, death_date) "
    "VALUES (%s, %s, %s, %s)"
)

populate_table(db_connection, db_cursor, insert_director, "director.csv", 4)
tno+=1

# create MOVIE table
db_cursor.execute("""CREATE TABLE MOVIE (film_id VARCHAR(50) NOT NULL, 
                                          rating FLOAT NOT NULL, 
                                          num_of_votes INT,
                                          release_date INT, 
                                          duration INT, 
                                          name VARCHAR(100) NOT NULL,
                                          director_id VARCHAR(50) NOT NULL,
                                          PRIMARY KEY(film_id))""")

insert_movie = (
    "INSERT INTO MOVIE(film_id, rating, num_of_votes, release_date, duration,name,director_id) "
    "VALUES (%s, %s, %s, %s, %s, %s,%s)"
)

populate_table(db_connection, db_cursor, insert_movie, "movie.csv", 7)
tno+=1

#create TV-SERIES table
db_cursor.execute("""CREATE TABLE TV_SERIES (series_id VARCHAR(50) NOT NULL, 
                                          name VARCHAR(100) NOT NULL, 
                                          rating FLOAT, 
                                          num_of_votes INT,
                                          release_date INT,
                                          num_of_episodes INT,
                                          PRIMARY KEY(series_id))""")

insert_tv_series = (
    "INSERT INTO TV_SERIES(series_id, name, rating, num_of_votes, release_date, num_of_episodes) "
    "VALUES (%s, %s, %s, %s, %s, %s)"
)

populate_table(db_connection, db_cursor, insert_tv_series, "tv_series.csv", 6)
tno+=1

# create Actor table
db_cursor.execute("""CREATE TABLE ACTOR (actor_id VARCHAR(50) NOT NULL, 
                                          name VARCHAR(100) NOT NULL, 
                                          birth_date INT, 
                                          death_date INT,
                                          PRIMARY KEY(actor_id))""")

insert_actor = (
    "INSERT INTO ACTOR(actor_id, name, birth_date, death_date) "
    "VALUES (%s, %s, %s, %s)"
)

populate_table(db_connection, db_cursor, insert_actor, "actors.csv", 4)
tno+=1


# create plays_in_movies table
db_cursor.execute("""CREATE TABLE PLAYS_IN_MOVIES (actor_id VARCHAR(50) NOT NULL, 
                                          film_id VARCHAR(50) NOT NULL, 
                                          PRIMARY KEY(actor_id, film_id))""")

insert_plays_in_movies = (
    "INSERT INTO PLAYS_IN_MOVIES(film_id, actor_id)"
    "VALUES (%s, %s)"
)

populate_table(db_connection, db_cursor, insert_plays_in_movies, "plays_in_movies.csv", 2)
tno+=1

# create plays_in_series table
db_cursor.execute("""CREATE TABLE PLAYS_IN_SERIES (actor_id VARCHAR(50) NOT NULL, 
                                          series_id VARCHAR(50) NOT NULL, 
                                          PRIMARY KEY(actor_id, series_id))""")

insert_plays_in_series = (
    "INSERT INTO PLAYS_IN_SERIES(series_id, actor_id) "
    "VALUES (%s, %s)"
)

populate_table(db_connection, db_cursor, insert_plays_in_series, "plays_in_series.csv", 2)
tno+=1

# create episode table
db_cursor.execute("""CREATE TABLE EPISODE (series_id VARCHAR(50) NOT NULL,
                                          season_no INT, 
                                          no INT, 
                                          title VARCHAR(100),
                                          duration INT,
                                          rating FLOAT,
                                          PRIMARY KEY(series_id, season_no, no))""")

insert_episode = (
    "INSERT INTO EPISODE(series_id, season_no, no, title, duration, rating) "
    "VALUES (%s, %s, %s, %s, %s, %s)"
)

populate_table(db_connection, db_cursor, insert_episode, "episode.csv", 6)
tno+=1

# create genre_movies table
db_cursor.execute("""CREATE TABLE GENRE_MOVIES (genre VARCHAR(50) NOT NULL, 
                                          film_id VARCHAR(50) NOT NULL, 
                                          PRIMARY KEY(genre, film_id))""")

insert_genre_movies = (
    "INSERT INTO GENRE_MOVIES(film_id, genre)"
    "VALUES (%s, %s)"
)

populate_table(db_connection, db_cursor, insert_genre_movies, "genre_movies.csv", 2)
tno+=1

# create genre_series table
db_cursor.execute("""CREATE TABLE GENRE_SERIES (genre VARCHAR(50) NOT NULL, 
                                          series_id VARCHAR(50) NOT NULL, 
                                          PRIMARY KEY(genre, series_id))""")

insert_genre_series= (
    "INSERT INTO GENRE_SERIES(series_id, genre)"
    "VALUES (%s, %s)"
)

populate_table(db_connection, db_cursor, insert_genre_series, "genre_series.csv", 2)
print('Successfully created all necessary tables')


