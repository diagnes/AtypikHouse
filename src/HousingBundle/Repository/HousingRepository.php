<?php

namespace HousingBundle\Repository;

use AtypikHouseBundle\Enum\ReservationStateEnum;
use Doctrine\ORM\EntityRepository;

/**
 * HousingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HousingRepository extends EntityRepository
{

    /**
     * Get the top travel city
     *
     * @param array $queryInfos Get all infos for the research
     *
     * @return array
     */
    public function getHousingByQuery($queryInfos)
    {
        $sql = '
            SELECT
              h.id
            FROM housing AS h
              INNER JOIN address AS ad ON h.address_id = ad.id
              LEFT JOIN housing_undisponibility AS hu ON h.id = hu.housing_id
            WHERE
              (
                ad.city LIKE :destination
                OR ad.city LIKE :destination
                OR ad.country LIKE :destination
                OR h.title LIKE :destination
              )
              AND h.max_resident >= :resident
              AND h.deleted_at IS NULL
            GROUP BY h.id
        ';

        $connection = $this->_em->getConnection();
        $query = $connection->prepare($sql);
        $destination = isset($queryInfos['destination']) ? '%'.$queryInfos['destination'].'%' : '%%';
        $resident = $queryInfos['resident'] ?? 0;
        $query->bindParam('destination', $destination);
        $query->bindParam('resident', $resident);
        $query->execute();

        $housings = $query->fetchAll();
        $housingIds = [];
        foreach ($housings as $housing) {
            $housingIds[] = $housing['id'];
        }
        return $housingIds;
    }

    /**
     * Get the average score review for a house
     *
     * @param int $id Get the targeted Housing
     *
     * @return float|int
     */
    public function getHouseAverageScore(int $id)
    {
        $sql = '
            SELECT
              SUM(hnv.value) as totalScore,
              COUNT(hnv.id) as coeff
            FROM housing AS h
              INNER JOIN reservation AS r ON r.housing_id = h.id
              INNER JOIN housing_notation AS hn ON hn.id = r.review_id
              INNER JOIN housing_notation_value AS hnv ON hnv.notation_id = hn.id
            WHERE h.id = :id AND r.state = :done
        ';
        $connection = $this->_em->getConnection();
        $query = $connection->prepare($sql);
        $done = ReservationStateEnum::DONE;
        $query->bindParam('id', $id);
        $query->bindParam('done', $done);
        $query->execute();
        $score = $query->fetchAll();

        return ((int)$score[0]['coeff'] > 0) ? $score[0]['totalScore'] / $score[0]['coeff'] : 0 ;
    }

    /**
     * Get the best notation for a house
     *
     * @param int $id Get the targeted Housing
     *
     * @return array
     */
    public function getBestNotationHouse(int $id)
    {
        $sql = '
            SELECT
              SUM(hnv.value) as totalScore,
              COUNT(hnv.id) as coeff,
              hn.description,
              upi.firstname,
              upi.lastname,
              ad.country,
              hn.created_at
            FROM housing AS h
              INNER JOIN reservation AS r ON r.housing_id = h.id
              INNER JOIN user AS u ON u.id = r.user_id
              INNER JOIN user_personal_infos AS upi ON upi.id = u.personal_info
              INNER JOIN address AS ad ON ad.id = upi.address_id
              INNER JOIN housing_notation AS hn ON hn.id = r.review_id
              INNER JOIN housing_notation_value AS hnv ON hnv.notation_id = hn.id
            WHERE h.id = :id AND r.state = :done
            GROUP BY r.id
            ORDER BY totalScore DESC
            LIMIT 1
        ';

        $connection = $this->_em->getConnection();
        $query = $connection->prepare($sql);
        $done = ReservationStateEnum::DONE;
        $query->bindParam('id', $id);
        $query->bindParam('done', $done);
        $query->execute();

        return $query->fetchAll()[0];
    }



    /**
     * Get the top travel city
     *
     * @return array
     */
    public function getTopCityTravel()
    {
        $sql = '
            SELECT
              ad.city as city,
              ad.country as country,
              COUNT(h.id) as houseCount
            FROM housing AS h
              INNER JOIN address AS ad ON h.address_id = ad.id
            GROUP BY ad.city
            ORDER BY houseCount
        ';

        $connection = $this->_em->getConnection();
        $query = $connection->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }

    /**
     * Get the top travel city
     *
     * @return array
     */
    public function getTotalHousing()
    {
        $sql = '
            SELECT
              COUNT(h.id) as total
            FROM housing AS h
        ';

        $connection = $this->_em->getConnection();
        $query = $connection->prepare($sql);
        $query->execute();

        return $query->fetchAll()[0]['total'];
    }
}
