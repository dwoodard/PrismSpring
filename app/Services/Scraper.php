<?php

namespace App\Services;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\BrowserKit\CookieJar;




/*******
 * Usage:
 *
 * Instantiate the Scraper service
 * use App\Services\Scraper;
 *
 * $scraper = new Scraper();
 *
 * 1. Make a GET request to a webpage
 * $crawler = $scraper->request('GET', 'https://example.com');
 * 
 * Extract and print the page title using DomCrawler
 * $title = $crawler->filter('title')->text();
 * echo "Page Title: " . $title . PHP_EOL;
 *
 * 2. Click a link by its text (e.g., 'About Us')
 * $crawler = $scraper->clickLink('About Us');
 * echo "Navigated to About Us page." . PHP_EOL;
 *
 * 3. Submit a form by specifying the button text (e.g., 'Submit') and form fields
 * $crawler = $scraper->submitForm('Submit', [
 *     'name'  => 'John Doe',
 *     'email' => 'john@example.com'
 * ]);
 * echo "Form submitted." . PHP_EOL;
 *
 * 4. Retrieve the last HTTP response and print its status code
 * $response = $scraper->getResponse();
 * echo "Last Response Status: " . $response->getStatusCode() . PHP_EOL;
 *
 * 5. Set a custom cookie (e.g., a user identifier)
 * $scraper->setCookie('user', 'john_doe', strtotime('+1 day'));
 * echo "Custom cookie has been set." . PHP_EOL;
 *
 * 6. Navigate through the browser history: go back then forward
 * $crawler = $scraper->back();
 * echo "Went back in history." . PHP_EOL;
 *
 * $crawler = $scraper->forward();
 * echo "Went forward in history." . PHP_EOL;
 *
 * 7. Reset the scraper (clears history and cookies)
 * $scraper->restart();
 * echo "Browser restarted, history and cookies cleared." . PHP_EOL;
 *
 ********/



class Scraper
{
    protected HttpBrowser $browser;

    /**
     * Create a new Scraper instance.
     *
     * @param array      $clientOptions Options for the HTTP client.
     * @param CookieJar|null $cookieJar  Optional cookie jar for handling cookies.
     */
    public function __construct(array $clientOptions = [], ?CookieJar $cookieJar = null)
    {
        // Create an HTTP client with any passed options.
        $httpClient = HttpClient::create($clientOptions);
        // Initialize the HttpBrowser with the HttpClient and optional CookieJar.
        $this->browser = new HttpBrowser($httpClient, null, $cookieJar);
    }

    /**
     * Make a basic HTTP request.
     */
    public function request(
        string $method,
        string $url,
        array $parameters = [],
        array $files = [],
        array $server = []
    ): Crawler {
        return $this->browser->request($method, $url, $parameters, $files, $server);
    }

    /**
     * Make a JSON request.
     */
    public function jsonRequest(string $method, string $url, array $parameters = []): Crawler
    {
        return $this->browser->jsonRequest($method, $url, $parameters);
    }

    /**
     * Make an AJAX (XML HTTP) request.
     */
    public function xmlHttpRequest(string $method, string $url, array $parameters = []): Crawler
    {
        return $this->browser->xmlHttpRequest($method, $url, $parameters);
    }

    /**
     * Simulate clicking a link by its text content.
     */
    public function clickLink(string $linkText, array $serverParameters = []): Crawler
    {
        return $this->browser->clickLink($linkText, $serverParameters);
    }

    /**
     * Simulate clicking a specific Link element.
     */
    public function clickLinkElement($link, array $serverParameters = []): Crawler
    {
        return $this->browser->click($link, $serverParameters);
    }

    /**
     * Submit a form using the button identifier.
     */
    public function submitForm(
        string $button,
        array $formValues = [],
        ?string $method = null,
        array $server = []
    ): Crawler {
        return $this->browser->submitForm($button, $formValues, $method, $server);
    }

    /**
     * Submit a Form object directly.
     */
    public function submitFormObject($form): Crawler
    {
        return $this->browser->submit($form);
    }

    /**
     * Retrieve the current HTTP response.
     */
    public function getResponse()
    {
        return $this->browser->getResponse();
    }

    /**
     * Access the CookieJar.
     */
    public function getCookieJar(): CookieJar
    {
        return $this->browser->getCookieJar();
    }

    /**
     * Set a cookie manually.
     */
    public function setCookie(
        string $name,
        string $value,
        int $expires,
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $httpOnly = true,
        string $sameSite = Cookie::SAMESITE_LAX
    ): void {
        $cookie = new Cookie($name, $value, $expires, $path, $domain, $secure, $httpOnly, $sameSite);
        $this->getCookieJar()->set($cookie);
    }

    /**
     * Navigate back in the browser history.
     */
    public function back(): Crawler
    {
        return $this->browser->back();
    }

    /**
     * Navigate forward in the browser history.
     */
    public function forward(): Crawler
    {
        return $this->browser->forward();
    }

    /**
     * Reset the browser history and clear cookies.
     */
    public function restart(): void
    {
        $this->browser->restart();
    }
}
