<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VlsmController extends Controller
{
    public function calculate(Request $request)
    {
        $ip = $request->input('ip');
        $mask = $request->input('mask');
        $subnets = $request->input('subnet_name');
        $hostsNeeded = $request->input('hosts_needed');

        // Validate IP and Mask
        if (!filter_var($ip, FILTER_VALIDATE_IP) || !is_numeric($mask) || $mask < 1 || $mask > 32) {
            return redirect('/')->with('error', 'Invalid IP address or mask.');
        }

        // Combine subnets and hosts needed into a single array
        $subnetsData = [];
        for ($i = 0; $i < count($subnets); $i++) {
            $subnetsData[] = [
                'name' => $subnets[$i],
                'hosts' => $hostsNeeded[$i],
            ];
        }

        // Sort subnets by hosts needed in descending order
        usort($subnetsData, function($a, $b) {
            return $b['hosts'] - $a['hosts'];
        });

        // Check if the initial mask can support the required hosts
        if (!$this->canSupportHosts($ip, $mask, $subnetsData)) {
            return redirect('/')->with('error', 'The initial mask cannot support the required number of hosts.');
        }

        // Calculate subnets
        $results = $this->calculateVlsm($ip, $mask, $subnetsData);

        return view('vlsm.results', ['subredes' => $results]);
    }

    private function calculateVlsm($ip, $mask, $subnetsData)
    {
        $subnets = [];
        $currentIp = ip2long($ip);

        foreach ($subnetsData as $subnet) {
            $hosts = $subnet['hosts'];
            $neededBits = ceil(log($hosts + 2, 2));
            $subnetMask = 32 - $neededBits;
            $hostsAvailable = pow(2, $neededBits) - 2;
            $unusedHosts = $hostsAvailable - $hosts;

            $networkAddress = long2ip($currentIp);
            $broadcastAddress = long2ip($currentIp + pow(2, 32 - $subnetMask) - 1);
            $firstHost = long2ip($currentIp + 1);
            $lastHost = long2ip($currentIp + pow(2, 32 - $subnetMask) - 2);

            $subnets[] = [
                'nome' => $subnet['name'],
                'hosts_necessarios' => $hosts,
                'hosts_disponiveis' => $hostsAvailable,
                'hosts_nao_utilizados' => $unusedHosts,
                'endereco_rede' => $networkAddress,
                'mascara' => $this->maskToIp($subnetMask),
                'faixa_utilizavel' => "$firstHost - $lastHost",
                'endereco_broadcast' => $broadcastAddress,
            ];

            $currentIp += pow(2, 32 - $subnetMask);
        }

        return $subnets;
    }

    private function canSupportHosts($ip, $mask, $subnetsData)
    {
        $networkBits = 32 - $mask;
        $availableAddresses = pow(2, $networkBits); // Total de endereços disponíveis na sub-rede maior
        $requiredAddresses = 0;

        foreach ($subnetsData as $subnet) {
            $hosts = $subnet['hosts'];
            $neededBits = ceil(log($hosts + 2, 2));
            $subnetAddresses = pow(2, $neededBits); // Inclui o endereço de rede e de broadcast
            $requiredAddresses += $subnetAddresses;
        }

        // Verifica se a quantidade total de endereços requisitados é menor ou igual à quantidade disponível
        return $availableAddresses >= $requiredAddresses;
    }

    private function maskToIp($mask)
    {
        return long2ip(-1 << (32 - $mask));
    }
}

?>
