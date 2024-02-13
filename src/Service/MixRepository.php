<?php
namespace App\Service;
use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MixRepository
{
    private string $mixesFilePath;

    public function __construct(
        private HttpClientInterface $githubContentClient,
        private CacheInterface $cache,
        private bool $isDebug
    ) {
        // Définissez le chemin du fichier mixes.json local
        $this->mixesFilePath = __DIR__ . '/../../resources/data/mixes.json';
    }

    public function findAll(): array
    {
        // Vérifiez si le fichier local existe
        if (file_exists($this->mixesFilePath)) {
            // Lisez le contenu du fichier et décodez-le
            $mixesData = json_decode(file_get_contents($this->mixesFilePath), true);
            return $mixesData;
        } else {
            // Si le fichier local n'existe pas, utilisez le client HTTP pour récupérer les données
            $mixesData = $this->cache->get('mixes_data', function(CacheItemInterface $cacheItem) {
                $cacheItem->expiresAfter($this->isDebug ? 5 : 60);
                $response = $this->githubContentClient->request('GET', 'https://raw.githubusercontent.com/SymfonyCasts/vinyl-mixes/main/mixes.json');
                return $response->toArray();
            });
            return $mixesData;
        }
    }
}
