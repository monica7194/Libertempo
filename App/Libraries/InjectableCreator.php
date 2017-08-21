<?php
namespace App\Libraries;

/**
 * Gestionnaire minimal d'injection de dépendances
 *
 * @since 1.10
 * @author Prytoegrian <prytoegrian@protonmail.com>
 * @see Tests\Units\App\Libraries\InjectableCreator
 *
 * Peut être contacté par tout ceux qui requierent un injectable
 */
class InjectableCreator
{

    public function __construct(\includes\SQL $db)
    {
        $this->db = $db;
    }

    /**
     * @var \includes\SQL
     */
    private $db;

    /**
     * Retourne un injectable bien construit (avec ses propres dépendances)
     *
     * @param string $classname
     *
     * @return object
     * @throws \LogicException si la construction est interdite
     */
    public function get($classname)
    {
        if (!class_exists($classname) || 'App\\' !== substr($classname, 0, 4)) {
            throw new \LogicException('Class « ' . $classname . ' » loading is forbidden');
        }

        switch ($classname) {
            case 'App\Libraries\Calendrier\Evenements\Weekend':
            case 'App\Libraries\Calendrier\Evenements\Ferie':
            case 'App\Libraries\Calendrier\Evenements\Fermeture':
            case 'App\Libraries\Calendrier\Evenements\Conge':
            case 'App\Libraries\Calendrier\Evenements\EchangeRtt':
            case 'App\Libraries\Calendrier\Evenements\Heure\Additionnelle':
            case 'App\Libraries\Calendrier\Evenements\Heure\Repos':
                return new $classname($this->db);

            default:
                throw new \LogicException('Unknown « ' . $classname . ' »');
        }
    }
}