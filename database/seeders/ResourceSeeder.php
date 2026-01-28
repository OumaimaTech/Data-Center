<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resource;
use App\Models\Category;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $serverCategory = Category::where('name', 'Serveurs')->first();
        $vmCategory = Category::where('name', 'Machines virtuelles')->first();
        $storageCategory = Category::where('name', 'Stockage')->first();
        $networkCategory = Category::where('name', 'Équipements réseau')->first();

        // Serveurs physiques
        Resource::create([
            'name' => 'Serveur Dell PowerEdge R740',
            'category_id' => $serverCategory->id,
            'description' => 'Serveur haute performance pour calcul intensif',
            'specifications' => [
                'CPU' => 'Intel Xeon Gold 6248R (2x24 cores)',
                'RAM' => '256 GB DDR4',
                'Storage' => '4x 2TB SSD NVMe',
                'Network' => '4x 10Gb Ethernet'
            ],
            'location' => 'Baie A-12, Rack 3',
            'status' => 'disponible',
        ]);

        Resource::create([
            'name' => 'Serveur HP ProLiant DL380 Gen10',
            'category_id' => $serverCategory->id,
            'description' => 'Serveur polyvalent pour applications web',
            'specifications' => [
                'CPU' => 'Intel Xeon Silver 4214 (2x12 cores)',
                'RAM' => '128 GB DDR4',
                'Storage' => '8x 1TB SAS',
                'Network' => '2x 10Gb Ethernet'
            ],
            'location' => 'Baie A-12, Rack 5',
            'status' => 'disponible',
        ]);

        Resource::create([
            'name' => 'Serveur Supermicro GPU Server',
            'category_id' => $serverCategory->id,
            'description' => 'Serveur optimisé pour IA et Machine Learning',
            'specifications' => [
                'CPU' => 'AMD EPYC 7742 (2x64 cores)',
                'RAM' => '512 GB DDR4',
                'GPU' => '8x NVIDIA A100 80GB',
                'Storage' => '2x 4TB NVMe',
                'Network' => '2x 25Gb Ethernet'
            ],
            'location' => 'Baie B-05, Rack 2',
            'status' => 'en_maintenance',
        ]);

        // Machines virtuelles
        Resource::create([
            'name' => 'VM-WEB-001',
            'category_id' => $vmCategory->id,
            'description' => 'Machine virtuelle pour hébergement web',
            'specifications' => [
                'vCPU' => '4 cores',
                'RAM' => '16 GB',
                'Storage' => '200 GB SSD',
                'OS' => 'Ubuntu 22.04 LTS'
            ],
            'location' => 'Cluster VMware ESXi-01',
            'status' => 'disponible',
        ]);

        Resource::create([
            'name' => 'VM-DB-002',
            'category_id' => $vmCategory->id,
            'description' => 'Machine virtuelle pour base de données',
            'specifications' => [
                'vCPU' => '8 cores',
                'RAM' => '32 GB',
                'Storage' => '500 GB SSD',
                'OS' => 'CentOS 8'
            ],
            'location' => 'Cluster VMware ESXi-02',
            'status' => 'reserve',
        ]);

        Resource::create([
            'name' => 'VM-DEV-003',
            'category_id' => $vmCategory->id,
            'description' => 'Machine virtuelle pour développement',
            'specifications' => [
                'vCPU' => '2 cores',
                'RAM' => '8 GB',
                'Storage' => '100 GB SSD',
                'OS' => 'Windows Server 2022'
            ],
            'location' => 'Cluster VMware ESXi-01',
            'status' => 'disponible',
        ]);

        // Stockage
        Resource::create([
            'name' => 'NetApp FAS8300',
            'category_id' => $storageCategory->id,
            'description' => 'Baie de stockage NAS haute capacité',
            'specifications' => [
                'Capacity' => '500 TB',
                'Type' => 'NAS',
                'Protocol' => 'NFS, CIFS, iSCSI',
                'Performance' => '10 GB/s throughput'
            ],
            'location' => 'Baie C-08, Rack 1-4',
            'status' => 'disponible',
        ]);

        Resource::create([
            'name' => 'Dell EMC Unity 650F',
            'category_id' => $storageCategory->id,
            'description' => 'Stockage SAN Flash pour performances élevées',
            'specifications' => [
                'Capacity' => '200 TB Flash',
                'Type' => 'SAN',
                'Protocol' => 'FC, iSCSI',
                'Performance' => '2M IOPS'
            ],
            'location' => 'Baie C-10, Rack 2',
            'status' => 'disponible',
        ]);

        // Équipements réseau
        Resource::create([
            'name' => 'Cisco Nexus 9300',
            'category_id' => $networkCategory->id,
            'description' => 'Switch core 100Gb',
            'specifications' => [
                'Ports' => '32x 100Gb QSFP28',
                'Throughput' => '6.4 Tbps',
                'Features' => 'VXLAN, BGP, OSPF'
            ],
            'location' => 'Salle réseau principale',
            'status' => 'disponible',
        ]);

        Resource::create([
            'name' => 'Juniper QFX5200',
            'category_id' => $networkCategory->id,
            'description' => 'Switch ToR 25/100Gb',
            'specifications' => [
                'Ports' => '48x 25Gb SFP28 + 8x 100Gb QSFP28',
                'Throughput' => '3.2 Tbps',
                'Features' => 'EVPN, VXLAN'
            ],
            'location' => 'Baie A-12',
            'status' => 'disponible',
        ]);

        Resource::create([
            'name' => 'Firewall Palo Alto PA-5250',
            'category_id' => $networkCategory->id,
            'description' => 'Pare-feu nouvelle génération',
            'specifications' => [
                'Throughput' => '64 Gbps',
                'Features' => 'IPS, URL Filtering, Threat Prevention',
                'Interfaces' => '8x 10Gb SFP+'
            ],
            'location' => 'Salle réseau DMZ',
            'status' => 'disponible',
        ]);
    }
}
