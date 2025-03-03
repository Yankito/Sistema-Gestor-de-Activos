use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    protected $proxies = '*'; // Confía en todos los proxies (Cloud Run)
    protected $headers = Request::HEADER_X_FORWARDED_AWS_ELB; // Maneja X-Forwarded-Proto
}
