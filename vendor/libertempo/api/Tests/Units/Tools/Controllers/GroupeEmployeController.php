<?php declare(strict_types = 1);
namespace LibertAPI\Tests\Units\Tools\Controllers;

use LibertAPI\Groupe\Employe\EmployeEntite;

/**
 * Classe de test du contrôleur d'un employé de groupe
 *
 * @author Prytoegrian <prytoegrian@protonmail.com>
 * @author Wouldsmina
 *
 * @since 1.0
 */
final class GroupeEmployeController extends \LibertAPI\Tests\Units\Tools\Libraries\AController
{
    /**
     * {@inheritdoc}
     */
    protected function initRepository()
    {
        $this->mockGenerator->orphanize('__construct');
        $this->mockGenerator->shuntParentClassCalls();
        $this->repository = new \mock\LibertAPI\Groupe\Employe\EmployeRepository();
    }

    /**
     * {@inheritdoc}
     */
    protected function initEntite()
    {
        $this->entite = new EmployeEntite([
            'id' => uniqid(),
            'groupeId' => 97323,
            'login' => 'Lewis',
        ]);
    }

    /*************************************************
     * GET
     *************************************************/

    /**
     * Teste la méthode get d'une liste trouvée
     */
    public function testGetFound()
    {
        $this->calling($this->request)->getQueryParams = [];
        $this->calling($this->repository)->getList = [$this->entite,];
        $this->newTestedInstance($this->repository, $this->router);
        $response = $this->testedInstance->get($this->request, $this->response, []);
        $data = $this->getJsonDecoded($response->getBody());

        $this->integer($response->getStatusCode())->isIdenticalTo(200);
        $this->array($data)
            ->integer['code']->isIdenticalTo(200)
            ->string['status']->isIdenticalTo('success')
            ->string['message']->isIdenticalTo('OK')
            //->array['data']->hasSize(1) // TODO: l'asserter atoum en sucre syntaxique est buggé, faire un ticket
        ;
        $this->array($data['data'][0])->hasKey('login');
    }

    /**
     * Teste la méthode get d'une liste non trouvée
     */
    public function testGetNotFound()
    {
        $this->calling($this->request)->getQueryParams = [];
        $this->calling($this->repository)->getList = function () {
            throw new \UnexpectedValueException('');
        };
        $this->newTestedInstance($this->repository, $this->router);
        $response = $this->testedInstance->get($this->request, $this->response, []);

        $this->assertSuccessEmpty($response);
    }

    /**
     * Teste le fallback de la méthode get d'une liste
     */
    public function testGetFallback()
    {
        $this->calling($this->request)->getQueryParams = [];
        $this->calling($this->repository)->getList = function () {
            throw new \Exception('');
        };
        $this->newTestedInstance($this->repository, $this->router);

        $response = $this->testedInstance->get($this->request, $this->response, []);
        $this->assertError($response);
    }
}
