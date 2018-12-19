<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        $courses = $this->prettifyCourseList($this->getF2015CoursesForAlissa());

        return $this->render(
            'default/index.html.twig',
            [
                "courseList" => $courses
            ]
        );
    }

    /**
     * @param $courses
     * @return array
     * takes in an array of database results
     * returns a reorganized, combined, jsonified version that can be used in a vue component
     */
    private function prettifyCourseList($courses){
        $rawCourses = $courses[0]["results"];
        $courseList = [];
        foreach ($rawCourses as $course){
            $courseDescription = $course["title"] . ' ' . $course['type'] . ' (' . (String) $course["ccode"] . ')';
            array_push($courseList, explode(' ', $courseDescription));
        }
        return json_encode($courseList);
    }

    /**
     * Queries the database for Alissa's Fall 2015 courses
     * @return array
     */
    private function getF2015CoursesForAlissa(){
        $connection = $this->getDoctrine()->getConnection();
        $qb = $connection->createQueryBuilder();

        $results = $qb->select('c.code as ccode, c.title as title, c.type as type')
            ->from('student_enrollments','s')
            ->leftjoin('s', 'courses', 'c', 's.course_code = c.code and s.term = c.term')
            ->where('s.student_id = :student_id')
            ->andWhere('c.department != :department')
            ->andWhere('c.term = :term')
            ->andWhere('s.result_code = :result_code')
            ->setParameter('student_id', 'XXXXXXXX')
            ->setParameter('department', "TEST")
            ->setParameter('term', 'F-2015')
            ->setParameter('result_code', 'E')
            ->execute()
            ->fetchAll();

        return [compact('results')];
    }
}
