<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Test Fonctionnel
 * Description of PlaylistControllerTest
 *
 * @author uu0✿
 */
class PlaylistControllerTest extends WebTestCase{
    /**
     * Test d'accès à la page des playlists
     */
    public function testAccesPage(){
       $client = static::createClient();
       $client->request('GET', '/playlists');
       $this->assertResponseStatusCodeSame(Response::HTTP_OK);
   }
    
   /**
    * Teste le tri des playlists selon leur nom, dans un ordre ascendant
    */
    public function testTriPlaylists()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'playlists/tri/name/ASC');
        $this->assertSelectorTextContains('th', 'playlist');
        $this->assertCount(4, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }
    
    /**
     * Teste le tri des playlists selon le nombre de formations 
     * dans l'ordre ascendant
     */
    public function testTriNbFormations()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'playlists/tri/nbformations/ASC');
        $this->assertSelectorTextContains('th', 'playlist');
        $this->assertCount(4, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Cours Informatique embarquée');
    }
    
    /**
     * Teste le filtrage des playlists selon la valeur recherchée
     */
    public function testFiltrePlaylists()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists'); 
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'bts'
        ]);
        //vérifie le nombre de lignes obtenues
        $this->assertCount(8, $crawler->filter('h5'));
        // vérifie si la formation correspond à la recherche
         $this->assertSelectorTextContains('h5', 'bts');
    }
    
    /**
     * Teste le filtrage des catégories selon la valeur recherchée
     */
    public function testFiltreCategories()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists/recherche/id/categories'); 
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'android'
        ]);
        //vérifie le nombre de lignes obtenues
        $this->assertCount(3, $crawler->filter('h5'));
        // vérifie si la formation correspond à la recherche
         $this->assertSelectorTextContains('h5', 'Cours Modèle relationnel et MCD');
    }
    
    /**
     * Test du lien qui redirige l'utilisateur vers la page de détail de la playlist
     */
    public function testLinkPlaylists() {
        $client = static::createClient();
        $client->request('GET','/playlists');
        $client->clickLink("Voir détail");
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/playlist/1', $uri);
    }
}