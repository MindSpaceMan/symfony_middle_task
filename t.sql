1)
SELECT CASE
            WHEN g.grade < 8 THEN 'low'
                ELSE s.name
        END AS name,
     g.grade AS grade,
        s.marks AS mark
FROM students s
         JOIN grade g
              ON s.marks BETWEEN g.min_mark AND g.max_mark
     ORDER BY
     -- 1) сначала по grade DESC (10→1)
     g.grade DESC,

     -- 2) для «старшей» группы (8–10) внутри равных grade — по имени ASC
     CASE WHEN g.grade BETWEEN 8 AND 10 THEN s.name END ASC,

    -- 3) для «младшей» группы (1–7) внутри равных grade — по marks ASC
    CASE WHEN g.grade < 8 THEN s.marks END ASC

    s.id ASC;

2)

DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS grade;

-- 1) Справочник грейдов (необязателен для вычисления, но полезен для JOIN/валидации)
CREATE TABLE grade (
                        grade     TINYINT UNSIGNED NOT NULL PRIMARY KEY,
                        min_mark  TINYINT UNSIGNED NOT NULL,
                        max_mark  TINYINT UNSIGNED NOT NULL,
                        CONSTRAINT ck_grade_bounds CHECK (min_mark <= max_mark AND min_mark >= 0 AND max_mark <= 100)
    ) ENGINE=InnoDB;

INSERT INTO grade (grade, min_mark, max_mark) VALUES
    (1,  0,  9),  (2, 10, 19), (3, 20, 29), (4, 30, 39), (5, 40, 49),
    (6, 50, 59), (7, 60, 69), (8, 70, 79), (9, 80, 89), (10, 90, 100);

-- 2) Таблица студентов
CREATE TABLE students (
                           id     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                           name   VARCHAR(100)    NOT NULL,
    marks  TINYINT UNSIGNED NOT NULL,
    -- вычисляемый grade из marks; STORED, чтобы индексировать
    grade  TINYINT UNSIGNED AS (
                                     CASE
                                     WHEN marks BETWEEN  0 AND  9  THEN 1
                                     WHEN marks BETWEEN 10 AND 19  THEN 2
                                     WHEN marks BETWEEN 20 AND 29  THEN 3
                                     WHEN marks BETWEEN 30 AND 39  THEN 4
                                     WHEN marks BETWEEN 40 AND 49  THEN 5
                                     WHEN marks BETWEEN 50 AND 59  THEN 6
                                     WHEN marks BETWEEN 60 AND 69  THEN 7
                                     WHEN marks BETWEEN 70 AND 79  THEN 8
                                     WHEN marks BETWEEN 80 AND 89  THEN 9
                                     WHEN marks BETWEEN 90 AND 100 THEN 10
                                     END
                                 ) STORED,
    CONSTRAINT pk_students PRIMARY KEY (id),
    CONSTRAINT ck_marks_range CHECK (marks BETWEEN 0 AND 100),
    INDEX idx_students_grade (grade)
    ) ENGINE=InnoDB;
