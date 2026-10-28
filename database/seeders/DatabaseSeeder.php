<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $admin = User::firstOrCreate(
            ['email' => 'mayurinakum@gmail.com'],
            [
                'name' => 'Mayuri Nakum (Admin)',
                'password' => Hash::make('mayuri21'),
                'role' => 'admin',
                'phone' => '+91 98765 43210',
                'address' => 'Chamunda Electrics Head Office',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'pincode' => '380001',
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Rahul Patel',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '+91 98250 12345',
                'address' => '402, Shivam Residency, Ring Road',
                'city' => 'Surat',
                'state' => 'Gujarat',
                'pincode' => '395007',
            ]
        );

        // 2. Categories
        $categoriesData = [
            [
                'name' => 'Wires & Cables',
                'slug' => 'wires-cables',
                'description' => 'Flame retardant house wires, industrial armoured cables, and submersible pump cables.',
                'icon' => 'bi-lightning-charge',
                'image' => 'images/categories/wires-cables.svg',
                'sort_order' => 1,
            ],
            [
                'name' => 'Switches & Sockets',
                'slug' => 'switches-sockets',
                'description' => 'Modern modular switches, power sockets, regulators, and designer cover plates.',
                'icon' => 'bi-toggle-on',
                'image' => 'images/categories/switches-sockets.svg',
                'sort_order' => 2,
            ],
            [
                'name' => 'Lighting & LED',
                'slug' => 'lighting-led',
                'description' => 'Energy efficient LED bulbs, ceiling panel lights, spotlights, and floodlights.',
                'icon' => 'bi-lightbulb',
                'image' => 'images/categories/lighting-led.svg',
                'sort_order' => 3,
            ],
            [
                'name' => 'Fans & Cooling',
                'slug' => 'fans-cooling',
                'description' => 'Smart BLDC ceiling fans, ventilation exhaust fans, and high-speed wall fans.',
                'icon' => 'bi-fan',
                'image' => 'images/categories/fans-appliances.svg',
                'sort_order' => 4,
            ],
            [
                'name' => 'Switchgears & MCB',
                'slug' => 'switchgears-mcbs',
                'description' => 'Miniature Circuit Breakers (MCB), RCCB shock protectors, and distribution boards.',
                'icon' => 'bi-shield-check',
                'image' => 'images/categories/switchgear-mcbs.svg',
                'sort_order' => 5,
            ],
            [
                'name' => 'Home Appliances',
                'slug' => 'home-appliances',
                'description' => 'Instant water heaters/geysers, room heaters, electric irons, and immersion rods.',
                'icon' => 'bi-plug',
                'image' => 'images/categories/home-appliances.svg',
                'sort_order' => 6,
            ],
            [
                'name' => 'Tools & Testing',
                'slug' => 'tools-meters',
                'description' => 'Digital multimeters, clamp meters, wire strippers, and electrician toolkit essentials.',
                'icon' => 'bi-tools',
                'image' => 'images/categories/tools-meters.svg',
                'sort_order' => 7,
            ],
            [
                'name' => 'Pipes & Fittings',
                'slug' => 'pipes-conduits',
                'description' => 'Heavy-duty PVC conduit pipes, bend fittings, junction boxes, and casing caping.',
                'icon' => 'bi-hdd-network',
                'image' => 'images/categories/pipes-conduits.svg',
                'sort_order' => 8,
            ],
        ];

        $categoryMap = [];
        foreach ($categoriesData as $c) {
            $cat = Category::updateOrCreate(['slug' => $c['slug']], $c);
            $categoryMap[$c['slug']] = $cat->id;
        }

        // 3. Brands
        $brandsData = [
            ['name' => 'Polycab', 'slug' => 'polycab', 'logo' => 'images/brands/polycab.svg'],
            ['name' => 'Havells', 'slug' => 'havells', 'logo' => 'images/brands/havells.svg'],
            ['name' => 'Anchor by Panasonic', 'slug' => 'anchor-panasonic', 'logo' => 'images/brands/anchor-panasonic.svg'],
            ['name' => 'Philips', 'slug' => 'philips', 'logo' => 'images/brands/philips.svg'],
            ['name' => 'Crompton', 'slug' => 'crompton', 'logo' => 'images/brands/crompton.svg'],
            ['name' => 'Schneider Electric', 'slug' => 'schneider', 'logo' => 'images/brands/schneider.svg'],
            ['name' => 'Finolex', 'slug' => 'finolex', 'logo' => 'images/brands/finolex.svg'],
            ['name' => 'Legrand', 'slug' => 'legrand', 'logo' => 'images/brands/legrand.svg'],
            ['name' => 'GM Modular', 'slug' => 'gm-modular', 'logo' => 'images/brands/gm-modular.svg'],
        ];

        $brandMap = [];
        foreach ($brandsData as $b) {
            $brand = Brand::updateOrCreate(['slug' => $b['slug']], $b);
            $brandMap[$b['slug']] = $brand->id;
        }

        // 4. Products
        $productsData = [
            [
                'name' => 'Polycab Flamegard 1.5 sq mm Wire (90m Red)',
                'slug' => 'polycab-flamegard-1-5-wire-red',
                'sku' => 'PC-FG-15-RED',
                'category_slug' => 'wires-cables',
                'brand_slug' => 'polycab',
                'price' => 1850.00,
                'sale_price' => 1499.00,
                'stock_quantity' => 120,
                'is_featured' => true,
                'image' => 'images/products/polycab-flamegard-wire.svg',
                'short_description' => 'Flame Retardant Low Smoke (FR-LSH) single core multi-strand copper wire 90-meter coil.',
                'description' => 'Polycab Flamegard wire is manufactured with 99.97% pure electrolytic grade bright annealed copper conductor with low conductor resistance. Specially formulated FR-LSH compound offers high oxygen index and low smoke emission during fires.',
                'technical_specs' => [
                    'Size' => '1.5 sq mm',
                    'Length' => '90 Meters',
                    'Conductor' => 'Electrolytic Copper',
                    'Insulation' => 'FR-LSH PVC',
                    'Voltage Rating' => '1100 Volts',
                    'Certification' => 'IS 694 / ISI Certified',
                    'Warranty' => 'Manufacturer Warranty',
                ],
            ],
            [
                'name' => 'Polycab Optima 2.5 sq mm Wire (90m Blue)',
                'slug' => 'polycab-optima-2-5-wire-blue',
                'sku' => 'PC-OPT-25-BLU',
                'category_slug' => 'wires-cables',
                'brand_slug' => 'polycab',
                'price' => 2950.00,
                'sale_price' => 2449.00,
                'stock_quantity' => 85,
                'is_featured' => false,
                'image' => 'images/products/polycab-wire-2-5.svg',
                'short_description' => 'High-power capacity copper wire for air conditioners, power sockets, and geysers.',
                'description' => 'Engineered for power-hungry appliances, this 2.5 sq mm wire prevents voltage drops and safely handles heavier electrical loads without overheating.',
                'technical_specs' => [
                    'Size' => '2.5 sq mm',
                    'Length' => '90 Meters',
                    'Conductor' => 'Multi-strand Copper',
                    'Current Capacity' => 'Up to 24 Amperes',
                    'Voltage' => '1100V',
                    'Warranty' => '1 Year',
                ],
            ],
            [
                'name' => 'Havells Monza EC 15-Litre Storage Geyser',
                'slug' => 'havells-monza-ec-15l-geyser',
                'sku' => 'HAV-MNZ-15L',
                'category_slug' => 'home-appliances',
                'brand_slug' => 'havells',
                'price' => 10490.00,
                'sale_price' => 7899.00,
                'stock_quantity' => 24,
                'is_featured' => true,
                'image' => 'images/products/havells-monza-geyser.svg',
                'short_description' => '5-Star rated instant water heater with Feroglas technology and Incoloy glass-coated heating element.',
                'description' => 'Havells Monza EC features heavy-duty ultra-thick cold rolled steel plates, Whirlflow technology for 20% higher hot water output, and adjustable temperature control knob with earth leakage protection.',
                'technical_specs' => [
                    'Capacity' => '15 Litres',
                    'Energy Rating' => 'BEE 5 Star',
                    'Tank Material' => 'Feroglas Coated Steel',
                    'Power' => '2000 Watts',
                    'Working Pressure' => '8 Bar (High-rise suitable)',
                    'Warranty' => '7 Years on Inner Tank, 2 Years Comprehensive',
                ],
            ],
            [
                'name' => 'Anchor Roma Classic 1-Way Modular Switch 6A White',
                'slug' => 'anchor-roma-classic-switch-6a',
                'sku' => 'ANC-ROM-SW6A',
                'category_slug' => 'switches-sockets',
                'brand_slug' => 'anchor-panasonic',
                'price' => 45.00,
                'sale_price' => 32.00,
                'stock_quantity' => 600,
                'is_featured' => true,
                'image' => 'images/products/anchor-roma-switch.svg',
                'short_description' => 'Smooth tactile operation, silver cadmium contacts, and fire-resistant polycarbonate body.',
                'description' => 'Anchor Roma is India’s most trusted modular switch series. Designed for over 100,000 smooth clicks, UV-stabilized to prevent yellowing over time.',
                'technical_specs' => [
                    'Type' => '1-Way Modular Switch',
                    'Rating' => '6 Amperes, 240V AC',
                    'Color' => 'Pure White',
                    'Material' => 'Polycarbonate (Flame Retardant)',
                    'Module Size' => '1 Module',
                    'Certification' => 'IS 3854',
                ],
            ],
            [
                'name' => 'Anchor Roma 6/16A Combined Power Socket with Shutter',
                'slug' => 'anchor-roma-combined-socket-16a',
                'sku' => 'ANC-ROM-SK16A',
                'category_slug' => 'switches-sockets',
                'brand_slug' => 'anchor-panasonic',
                'price' => 180.00,
                'sale_price' => 135.00,
                'stock_quantity' => 350,
                'is_featured' => false,
                'image' => 'images/products/anchor-roma-socket.svg',
                'short_description' => 'Universal 2-in-1 socket with child safety shutter and high grip brass terminals.',
                'description' => 'Perfect for refrigerators, microwaves, and washing machines. Child-safe shutter mechanism prevents accidental electrocution.',
                'technical_specs' => [
                    'Current' => '6A / 16A Dual Rating',
                    'Module Size' => '2 Module',
                    'Safety' => 'Integrated Child Safety Shutter',
                    'Voltage' => '240V',
                    'Warranty' => '10 Years',
                ],
            ],
            [
                'name' => 'Philips Stellar 15W Round LED Concealed Panel Light',
                'slug' => 'philips-stellar-15w-panel-light',
                'sku' => 'PH-STEL-15W',
                'category_slug' => 'lighting-led',
                'brand_slug' => 'philips',
                'price' => 699.00,
                'sale_price' => 449.00,
                'stock_quantity' => 150,
                'is_featured' => true,
                'image' => 'images/products/philips-stellar-led-panel.svg',
                'short_description' => 'Ultra slim recessed ceiling panel light with EyeComfort glare-free technology.',
                'description' => 'Philips Stellar delivers 1350 lumens of glare-free, uniform light distribution. Built-in surge protection up to 3kV protects against voltage spikes.',
                'technical_specs' => [
                    'Wattage' => '15 Watts',
                    'Color Temperature' => '6500K (Cool Day Light)',
                    'Lumen Output' => '1350 Lumens',
                    'Cutout Diameter' => '150mm (6 Inch)',
                    'Surge Protection' => '3.5 kV',
                    'Warranty' => '2 Years Replacement',
                ],
            ],
            [
                'name' => 'Philips 9W B22 LED Bulb (Pack of 4)',
                'slug' => 'philips-9w-led-bulb-pack-4',
                'sku' => 'PH-BLB-9W-4PK',
                'category_slug' => 'lighting-led',
                'brand_slug' => 'philips',
                'price' => 480.00,
                'sale_price' => 329.00,
                'stock_quantity' => 200,
                'is_featured' => false,
                'image' => 'images/products/philips-led-bulb-pack.svg',
                'short_description' => 'Energy saving warm/cool LED bulb pack for standard Indian B22 holders.',
                'description' => 'Long life up to 15,000 hours, up to 90% energy saving compared to standard incandescent bulbs.',
                'technical_specs' => [
                    'Wattage' => '9W (Equivalent to 60W bulb)',
                    'Base' => 'B22 Pin Base',
                    'Pack Size' => '4 Bulbs',
                    'Beam Angle' => '220 Degrees',
                    'Warranty' => '1 Year',
                ],
            ],
            [
                'name' => 'Crompton Energion HS 1200mm BLDC Ceiling Fan with Remote',
                'slug' => 'crompton-energion-hs-1200mm-bldc',
                'sku' => 'CRMP-ENRG-1200',
                'category_slug' => 'fans-cooling',
                'brand_slug' => 'crompton',
                'price' => 4599.00,
                'sale_price' => 3299.00,
                'stock_quantity' => 40,
                'is_featured' => true,
                'image' => 'images/products/crompton-energion-bldc-fan.svg',
                'short_description' => 'ActivBLDC technology saves up to 60% electricity bill. Consumes just 35 Watts.',
                'description' => 'Features point-anywhere smart RF remote with timer mode and sleep mode. Runs 3X longer on home inverters compared to regular induction motor fans.',
                'technical_specs' => [
                    'Sweep Size' => '1200 mm (48 Inch)',
                    'Motor' => 'BLDC (Brushless DC) 35W',
                    'Air Delivery' => '220 CMM',
                    'Speed' => '370 RPM',
                    'Control' => 'Smart RF Remote Included',
                    'Warranty' => '5 Years Onsite Warranty',
                ],
            ],
            [
                'name' => 'Orient Electric Hill Air 200mm Ventilation Exhaust Fan',
                'slug' => 'orient-electric-hill-air-200mm',
                'sku' => 'ORT-HA-200EX',
                'category_slug' => 'fans-cooling',
                'brand_slug' => 'crompton',
                'price' => 1450.00,
                'sale_price' => 1099.00,
                'stock_quantity' => 60,
                'is_featured' => false,
                'image' => 'images/products/orient-electric-exhaust-fan.svg',
                'short_description' => 'Rust-proof plastic body exhaust fan with back louver shutter for kitchen & bathroom.',
                'description' => 'Smooth silent operation, automatically opens back shutters when powered on to prevent dust and insects from entering.',
                'technical_specs' => [
                    'Blade Size' => '200 mm (8 Inch)',
                    'Speed' => '1350 RPM',
                    'Power' => '30W',
                    'Body' => 'Rust-proof ABS Plastic',
                    'Warranty' => '2 Years',
                ],
            ],
            [
                'name' => 'Schneider Acti9 32A C-Curve Double Pole (DP) MCB',
                'slug' => 'schneider-acti9-32a-dp-mcb',
                'sku' => 'SCH-ACT9-32DP',
                'category_slug' => 'switchgears-mcbs',
                'brand_slug' => 'schneider',
                'price' => 990.00,
                'sale_price' => 749.00,
                'stock_quantity' => 90,
                'is_featured' => true,
                'image' => 'images/products/schneider-mcb-double-pole.svg',
                'short_description' => 'Heavy duty 10kA breaking capacity for complete main incoming line protection.',
                'description' => 'World-renowned Schneider Acti9 series offers top-tier safety against overload currents and short circuits. Features bi-connect terminals and trip-free mechanism.',
                'technical_specs' => [
                    'Current' => '32 Amperes',
                    'Poles' => 'Double Pole (DP)',
                    'Tripping Curve' => 'C-Curve',
                    'Breaking Capacity' => '10 kA',
                    'Standard' => 'IEC 60898-1',
                    'Warranty' => '2 Years',
                ],
            ],
            [
                'name' => 'Havells 8-Way SPN Double Door Distribution Board',
                'slug' => 'havells-8-way-spn-db-box',
                'sku' => 'HAV-DB-8W-SPN',
                'category_slug' => 'switchgears-mcbs',
                'brand_slug' => 'havells',
                'price' => 1650.00,
                'sale_price' => 1280.00,
                'stock_quantity' => 35,
                'is_featured' => false,
                'image' => 'images/products/havells-distribution-board.svg',
                'short_description' => 'CRCA sheet steel enclosure with top and bottom conduit knockouts.',
                'description' => 'Double door design protects MCBs from accidental contact, dust, and moisture. Powder coated finish ensures lifelong rust resistance.',
                'technical_specs' => [
                    'Ways' => '8-Way SPN',
                    'Enclosure' => 'Double Door IP43',
                    'Material' => 'CRCA Steel Sheet',
                    'Color' => 'Pearl White / Grey',
                    'Warranty' => '1 Year',
                ],
            ],
            [
                'name' => 'Finolex 3-Core 4.0 sq mm Flat Submersible Cable (50m Coil)',
                'slug' => 'finolex-3-core-4-0-submersible-cable',
                'sku' => 'FIN-SUB-40-50M',
                'category_slug' => 'wires-cables',
                'brand_slug' => 'finolex',
                'price' => 7800.00,
                'sale_price' => 6499.00,
                'stock_quantity' => 20,
                'is_featured' => true,
                'image' => 'images/products/finolex-submersible-cable.svg',
                'short_description' => 'Heavy duty water-resistant cable for agricultural and residential borewell pump motors.',
                'description' => 'Finolex submersible flat cables are manufactured using bright electrolytic grade high conductivity copper. Sheathed with specially formulated PVC compound resistant to grease, water, and oil.',
                'technical_specs' => [
                    'Cores' => '3 Core Flat',
                    'Conductor Size' => '4.0 sq mm',
                    'Length' => '50 Meters',
                    'Sheath' => 'Special Water Resistant PVC',
                    'Voltage' => '1100 Volts',
                    'Application' => 'Submersible Pumps up to 5 HP',
                ],
            ],
            [
                'name' => 'GM 3012 4-Way Power Spike Guard Strip with Master Switch',
                'slug' => 'gm-modular-4-way-spike-guard',
                'sku' => 'GM-SPK-3012',
                'category_slug' => 'switches-sockets',
                'brand_slug' => 'gm-modular',
                'price' => 595.00,
                'sale_price' => 449.00,
                'stock_quantity' => 110,
                'is_featured' => false,
                'image' => 'images/products/gm-modular-4-way-extension.svg',
                'short_description' => 'Heavy-duty surge protector extension board with 2-meter cord and safety shutter.',
                'description' => 'Built-in thermal overload protection and MOV surge suppression to safeguard sensitive laptops, TVs, and home audio systems.',
                'technical_specs' => [
                    'Sockets' => '4 International Universal Sockets',
                    'Cable Length' => '2 Meters',
                    'Rating' => '10A 250V AC (2500 Watts Max)',
                    'Protection' => 'Overload Reset Fuse',
                    'Warranty' => '1 Year',
                ],
            ],
            [
                'name' => 'HTC Digital AC/DC Clamp Meter & Multimeter DT-201',
                'slug' => 'htc-digital-clamp-meter-dt201',
                'sku' => 'HTC-CLP-DT201',
                'category_slug' => 'tools-meters',
                'brand_slug' => 'schneider',
                'price' => 1850.00,
                'sale_price' => 1399.00,
                'stock_quantity' => 50,
                'is_featured' => true,
                'image' => 'images/products/digital-multimeter-clamp.svg',
                'short_description' => 'Professional non-contact AC current clamp tester with digital LCD display.',
                'description' => 'Essential gear for every electrician and contractor. Measures AC current up to 1000A, AC/DC voltage, resistance, and continuity with audible buzzer.',
                'technical_specs' => [
                    'Display' => '1999 Count Backlit LCD',
                    'AC Current' => '20A to 1000A Clamp',
                    'DC Voltage' => 'Up to 1000V',
                    'Jaw Opening' => '50mm',
                    'Includes' => 'Carrying Case & Test Probes',
                    'Warranty' => '1 Year',
                ],
            ],
            [
                'name' => 'Finolex 25mm Heavy Duty PVC Conduit Pipe (Pack of 10 x 3m)',
                'slug' => 'finolex-25mm-pvc-conduit-pipe',
                'sku' => 'FIN-CND-25MM-10PK',
                'category_slug' => 'pipes-conduits',
                'brand_slug' => 'finolex',
                'price' => 1300.00,
                'sale_price' => 999.00,
                'stock_quantity' => 75,
                'is_featured' => false,
                'image' => 'images/products/finolex-conduit-pipe.svg',
                'short_description' => 'High impact fire-retardant rigid electrical piping for concealed wall wiring.',
                'description' => 'Made from virgin PVC compound with zero lead content. Does not crack or bend under concrete slabs during construction.',
                'technical_specs' => [
                    'Outer Diameter' => '25 mm',
                    'Length' => '3 Meters Per Pipe',
                    'Quantity' => '10 Pipes (30 Meters Total)',
                    'Material' => 'Rigid PVC (Heavy Duty)',
                    'Standard' => 'IS 9537 Part 3',
                ],
            ],
            [
                'name' => 'Havells Stealth Air 1200mm Metallic Finish BLDC Fan',
                'slug' => 'havells-stealth-air-1200-metallic',
                'sku' => 'HAV-STL-1200',
                'category_slug' => 'fans-cooling',
                'brand_slug' => 'havells',
                'price' => 6490.00,
                'sale_price' => 4999.00,
                'stock_quantity' => 30,
                'is_featured' => true,
                'image' => 'images/products/havells-stealth-air-bldc.svg',
                'short_description' => 'Aerodynamic silent blades delivering 280 CMM massive air flow with ECO mode.',
                'description' => 'Metallic dual-tone finish with aerofoil ABS blades. Operates silently at 280 RPM with minimal power consumption of 38W.',
                'technical_specs' => [
                    'Sweep' => '1200 mm',
                    'Motor' => 'Inverter BLDC 38W',
                    'Air Delivery' => '280 CMM',
                    'Blades' => 'Aerodynamic ABS',
                    'Warranty' => '2 Years Comprehensive',
                ],
            ],
            [
                'name' => 'Legrand Mylinc 6A Indicator Bell Push Switch',
                'slug' => 'legrand-mylinc-bell-push-6a',
                'sku' => 'LEG-MYL-BP6A',
                'category_slug' => 'switches-sockets',
                'brand_slug' => 'legrand',
                'price' => 140.00,
                'sale_price' => 98.00,
                'stock_quantity' => 140,
                'is_featured' => false,
                'image' => 'images/products/legrand-mylinc-bell-push.svg',
                'short_description' => 'Sleek luxury doorbell switch with glowing LED indicator.',
                'description' => 'French minimalist styling with soft-touch action and built-in locator LED that glows in the dark.',
                'technical_specs' => [
                    'Current' => '6A 240V',
                    'Module' => '1 Module',
                    'Feature' => 'Night Glow Indicator LED',
                    'Finish' => 'Matte Polar White',
                    'Warranty' => '10 Years',
                ],
            ],
            [
                'name' => 'Wipro Garnet 50W Outdoor Waterproof LED Floodlight',
                'slug' => 'wipro-garnet-50w-led-floodlight',
                'sku' => 'WIP-GAR-50W-FLD',
                'category_slug' => 'lighting-led',
                'brand_slug' => 'philips',
                'price' => 2200.00,
                'sale_price' => 1599.00,
                'stock_quantity' => 45,
                'is_featured' => true,
                'image' => 'images/products/wipro-garnet-flood-light.svg',
                'short_description' => 'IP66 waterproof rated heavy die-cast aluminum flood light for gardens, parking, and billboards.',
                'description' => 'Bright 5000 lumen output with 4kV surge protection. Weather-resistant toughened glass cover resists rain, dust, and extreme heat.',
                'technical_specs' => [
                    'Wattage' => '50 Watts',
                    'Brightness' => '5000 Lumens',
                    'Water Resistance' => 'IP66 Ingress Protection',
                    'Body' => 'Die-cast Heavy Aluminum',
                    'Warranty' => '2 Years',
                ],
            ],
        ];

        $productModels = [];
        foreach ($productsData as $p) {
            $catId = $categoryMap[$p['category_slug']];
            $brandId = $brandMap[$p['brand_slug']] ?? null;
            unset($p['category_slug'], $p['brand_slug']);
            $p['category_id'] = $catId;
            $p['brand_id'] = $brandId;

            $product = Product::updateOrCreate(['sku' => $p['sku']], $p);
            $productModels[] = $product;
        }

        // 5. Coupons
        Coupon::updateOrCreate(
            ['code' => 'CHAMUNDA10'],
            [
                'type' => 'percent',
                'value' => 10.00,
                'min_order_amount' => 1000.00,
                'max_discount' => 500.00,
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'FLAT500'],
            [
                'type' => 'fixed',
                'value' => 500.00,
                'min_order_amount' => 5000.00,
                'max_discount' => null,
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'DIWALI20'],
            [
                'type' => 'percent',
                'value' => 20.00,
                'min_order_amount' => 3000.00,
                'max_discount' => 1000.00,
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
            ]
        );

        // 6. Seed Sample Order
        $sampleOrder = Order::firstOrCreate(
            ['order_number' => 'CE-20261012-001'],
            [
                'user_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone,
                'shipping_address' => $customer->address,
                'city' => $customer->city,
                'state' => $customer->state,
                'pincode' => $customer->pincode,
                'subtotal' => 4798.00,
                'discount_amount' => 479.80,
                'coupon_code' => 'CHAMUNDA10',
                'tax_amount' => 777.27,
                'shipping_charge' => 0.00,
                'total_amount' => 5095.47,
                'payment_method' => 'online_upi',
                'payment_status' => 'paid',
                'order_status' => 'processing',
                'tracking_number' => 'DTDC89410293IN',
                'notes' => 'Please deliver during daytime hours.',
            ]
        );

        if ($sampleOrder->wasRecentlyCreated && count($productModels) >= 2) {
            OrderItem::create([
                'order_id' => $sampleOrder->id,
                'product_id' => $productModels[0]->id,
                'product_name' => $productModels[0]->name,
                'product_sku' => $productModels[0]->sku,
                'price' => $productModels[0]->effective_price,
                'quantity' => 2,
                'subtotal' => $productModels[0]->effective_price * 2,
            ]);

            OrderItem::create([
                'order_id' => $sampleOrder->id,
                'product_id' => $productModels[5]->id,
                'product_name' => $productModels[5]->name,
                'product_sku' => $productModels[5]->sku,
                'price' => $productModels[5]->effective_price,
                'quantity' => 4,
                'subtotal' => $productModels[5]->effective_price * 4,
            ]);
        }

        // 7. Seed Sample Reviews
        if (count($productModels) > 0) {
            Review::firstOrCreate(
                ['product_id' => $productModels[0]->id, 'user_id' => $customer->id],
                [
                    'rating' => 5,
                    'comment' => 'Genuine Polycab copper wire! Used it for complete house rewiring, high heat tolerance and true 90-meter length coil.',
                    'is_approved' => true,
                ]
            );

            Review::firstOrCreate(
                ['product_id' => $productModels[2]->id, 'user_id' => $customer->id],
                [
                    'rating' => 5,
                    'comment' => 'Havells geyser heats water in less than 7 minutes. Very energy efficient and silent.',
                    'is_approved' => true,
                ]
            );
        }

        // 8. Seed Initial Hero Banners
        $bannersData = [
            [
                'title' => 'Powering Your Projects with 100% Genuine Supplies',
                'subtitle' => 'Polycab Wires • Havells Switchgear • Philips LED • BLDC Fans at direct wholesale pricing with GST invoice.',
                'badge_text' => 'FESTIVE MEGA ELECTRICAL SALE',
                'image_path' => 'images/banners/banner-mega-sale.svg',
                'link_url' => '/shop',
                'button_text' => 'Shop Mega Sale',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Heavy-Duty Wires, Cables & Modular Switchgear',
                'subtitle' => 'Polycab FR-LSH Copper Wires, Schneider MCBs, Finolex Submersible with bulk contractor rates.',
                'badge_text' => 'INDUSTRIAL & CONTRACTOR GRADE',
                'image_path' => 'images/banners/banner-cables-switchgear.svg',
                'link_url' => '/category/wires-cables',
                'button_text' => 'Order Bulk Wires',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Smart BLDC Ceiling Fans & Architectural LED Lights',
                'subtitle' => 'Save up to 65% on electricity with remote-controlled whisper quiet BLDC motors and 3-year onsite warranty.',
                'badge_text' => '5-STAR ENERGY SAVING LIVING',
                'image_path' => 'images/banners/banner-smart-fans-led.svg',
                'link_url' => '/category/fans-ventilation',
                'button_text' => 'Explore Smart Fans',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($bannersData as $bData) {
            \App\Models\Banner::firstOrCreate(
                ['image_path' => $bData['image_path']],
                $bData
            );
        }
    }
}

