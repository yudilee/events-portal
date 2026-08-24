<?php

namespace Database\Seeders;

use App\Models\BusinessUnit;
use App\Models\Event;
use App\Models\EventAgenda;
use App\Models\EventComment;
use App\Models\EventMedia;
use App\Models\GuestBookEntry;
use App\Models\HeroSlide;
use App\Models\PressRelease;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Business Units
        $mbUnit = BusinessUnit::create([
            'name' => 'Mercedes-Benz Authorized Dealer',
            'slug' => 'mercedes-benz',
            'subtitle' => 'Passenger Car & Commercial Vehicle Dealership',
            'description' => 'PT Hartono Raya Motor is the official authorized dealer for Mercedes-Benz passenger cars and heavy-duty commercial vehicles across Jakarta, Surabaya, Bali, and Semarang since 1970.',
            'icon' => 'Car',
            'badge_color' => 'teal',
            'sort_order' => 1,
        ]);

        $studioUnit = BusinessUnit::create([
            'name' => 'HR Auto Studio',
            'slug' => 'hr-auto-studio',
            'subtitle' => 'Body Repair, Paint & Premium Detailing',
            'description' => 'State-of-the-art body repair, ceramic coating, paint protection film (PPF), and executive auto salon using factory-approved techniques and eco-friendly paint booths.',
            'icon' => 'Sparkles',
            'badge_color' => 'cyan',
            'sort_order' => 2,
        ]);

        $rentUnit = BusinessUnit::create([
            'name' => 'Hartono Car Rental',
            'slug' => 'car-rental',
            'subtitle' => 'Luxury & Corporate Fleet Management',
            'description' => 'Premium vehicle rental and corporate fleet management solutions tailored for executive transport and enterprise logistics across Indonesia.',
            'icon' => 'Key',
            'badge_color' => 'gold',
            'sort_order' => 3,
        ]);

        $karoseriUnit = BusinessUnit::create([
            'name' => 'PT Rudy Darma Engineering',
            'slug' => 'karoseri',
            'subtitle' => 'Specialized Truck Body Manufacturing',
            'description' => 'Pioneering heavy truck body builder, dump trucks, box trailers, and specialized automotive body engineering built with extreme durability.',
            'icon' => 'Truck',
            'badge_color' => 'slate',
            'sort_order' => 4,
        ]);

        $heavyUnit = BusinessUnit::create([
            'name' => 'Hartono Heavy Transport',
            'slug' => 'heavy-transport',
            'subtitle' => 'Industrial Heavy Haulage & Logistics',
            'description' => 'Oversized cargo transportation, specialized lowbed haulage, and heavy machinery logistics across Java and Bali.',
            'icon' => 'Container',
            'badge_color' => 'orange',
            'sort_order' => 5,
        ]);

        // 2. Multi-Role Users
        $admin = User::create([
            'name' => 'Hartono Group Admin',
            'email' => 'admin@hartonomotor.com',
            'phone' => '+62 811-331-1306',
            'company' => 'Hartono Motor Group HQ',
            'role' => 'admin',
            'status' => 'active',
            'member_tier' => 'vip',
            'password' => Hash::make('P@ssword#hrm765.'),
        ]);

        $organizer = User::create([
            'name' => 'Event Organizer Team',
            'email' => 'organizer@hartonomotor-group.com',
            'phone' => '+62 877-8222-3724',
            'company' => 'PT Hartono Raya Motor Jakarta',
            'role' => 'event_organizer',
            'status' => 'active',
            'password' => Hash::make('password'),
        ]);

        $member = User::create([
            'name' => 'Bambang Soediro',
            'email' => 'member@hartonomotor-group.com',
            'phone' => '+62 812-8899-0011',
            'company' => 'PT Nusantara Makmur Sentosa',
            'role' => 'member',
            'status' => 'active',
            'member_tier' => 'vip',
            'password' => Hash::make('password'),
        ]);

        $newsAgency = User::create([
            'name' => 'Reza Aditya (AutonetMagz)',
            'email' => 'media@autonetmagz.com',
            'phone' => '+62 813-7766-5544',
            'company' => 'AutonetMagz Editorial Press',
            'role' => 'news_agency',
            'status' => 'active',
            'member_tier' => 'media',
            'password' => Hash::make('password'),
        ]);

        // 3. Featured Event: Customer Gathering & Soft Opening HR Auto Studio (Aug 27, 2026)
        $event1 = Event::create([
            'business_unit_id' => $studioUnit->id,
            'title' => 'Customer Gathering CV HRM Jakarta & Soft Opening HR Auto Studio',
            'slug' => 'customer-gathering-soft-opening-hr-auto-studio-2026',
            'subtitle' => 'Body Repair & Detailing Center Soft Launch & Customer Appreciation',
            'description' => 'You are cordially invited to celebrate the grand milestone of PT Hartono Raya Motor: Customer Gathering CV HRM Jakarta and the official Soft Opening of HR Auto Studio Body Repair & Detailing Center.',
            'content_html' => '<h2>Grand Automotive Milestone in Jakarta</h2><p>PT Hartono Raya Motor cordially invites valued clients, enterprise fleet partners, and automotive enthusiasts to our premier dual celebration: the <strong>Customer Gathering CV HRM Jakarta</strong> and the official <strong>Soft Opening of HR Auto Studio</strong>.</p><blockquote>"Through over 55 years of continuous growth, Hartono Group remains dedicated to pioneering service excellence across the automotive lifecycle."</blockquote><h3>Facility Highlights & Innovations</h3><ul><li><strong>Certified German Collision Repair Standards</strong> with laser chassis alignment.</li><li><strong>Eco-Friendly Waterborne Paint Booths</strong> with infrared curing systems.</li><li><strong>Luxury Auto Detailing & Ceramic Coatings</strong> by certified master craftsmen.</li></ul><p>Join our guided facility tour, witness the ceremonial tumpeng cutting, and enjoy exclusive sales & service incentives.</p>',
            'post_event_summary' => '<h2>An Unforgettable Gathering of Automotive Leaders</h2><p>We extend our deepest appreciation to over 150 distinguished VIP clients, fleet operators, and media representatives who joined us for the Soft Opening of HR Auto Studio.</p><p>The event concluded with great enthusiasm, marking a new benchmark in authorized vehicle body repair, detailing, and customer experience in Jakarta.</p>',
            'event_type' => 'customer_gathering',
            'visibility' => 'public',
            'date' => '2026-08-27',
            'start_time' => '10:00',
            'end_time' => '13:00',
            'timezone' => 'WIB',
            'venue_name' => 'PT Hartono Raya Motor Jakarta',
            'venue_address' => 'Jl. Raya Daan Mogot Km 10 No. 9, Jakarta Barat, DKI Jakarta 11710',
            'venue_map_url' => 'https://maps.google.com/?q=Hartono+Raya+Motor+Jakarta',
            'dress_code' => 'Smart Casual',
            'rsvp_contact' => 'HRM Jakarta Event Desk',
            'rsvp_phone' => '087782223724',
            'max_capacity' => 150,
            'registration_deadline' => '2026-08-26 18:00:00',
            'hero_image' => 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=1600&q=80',
            'status' => 'published',
            'is_registration_enabled' => true,
            'is_guestbook_enabled' => true,
            'is_gallery_enabled' => true,
            'is_post_event_enabled' => true,
            'post_event_published_at' => '2026-08-20 12:00:00',
            'created_by' => $admin->id,
        ]);

        // Agendas from invitation PDF
        $agendas = [
            ['09.45 - 10.15', '09:45', '10:15', 'Registration & Welcome Refreshments', 'Guest arrival, digital QR check-in, welcome drink, and networking at executive lounge.', 'Event Host', 'Master of Ceremonies', 1],
            ['10.15 - 10.25', '10:15', '10:25', 'Opening Remarks and Speech', 'Welcome speech by the HRM Board of Directors Representative.', 'HRM Board of Directors', 'Director', 2],
            ['10.25 - 10.35', '10:25', '10:35', 'Company Profile Video Presentation', 'Showcase of Hartono Group heritage since 1970 and integrated automotive ecosystem.', 'Hartono Media Team', 'Multimedia Showcase', 3],
            ['10.35 - 10.45', '10:35', '10:45', 'Product Presentation by HRM Product Expert', 'Detailed overview of Mercedes-Benz Commercial & Passenger product line.', 'HRM Product Specialist', 'Product Specialist', 4],
            ['10.45 - 11.00', '10:45', '11:00', 'Sales & After-Sales Programs and Special Offers', 'Exclusive event-only incentives, service packages, and corporate fleet programs.', 'HRM Management', 'Sales & After-Sales Head', 5],
            ['11.00 - 11.10', '11:00', '11:10', 'Remarks by HR Auto Studio Director', 'Introduction to state-of-the-art body repair, paint booth, and detailing facilities.', 'HR Auto Studio Director', 'Director', 6],
            ['11.10 - 11.25', '11:10', '11:25', 'Soft Opening, Ribbon-Cutting & Tumpeng Ceremony', 'Official inaugural ceremony marking the opening of HR Auto Studio.', 'Board & Management', 'BOD & Management', 7],
            ['11.25 - 11.45', '11:25', '11:45', 'Guided Tour of HR Auto Studio', 'Interactive tour through detailing bays, infrared baking booths, and aluminum repair areas.', 'Technical Lead', 'Studio Operations Manager', 8],
            ['11.45 - 12.30', '11:45', '12:30', 'Networking Executive Lunch', 'Gourmet luncheon and live acoustic music accompaniment.', 'Master of Ceremonies', 'Host', 9],
            ['12.30 - 12.45', '12:30', '12:45', 'Grand Lucky Draw', 'Exciting giveaways including ceramic coating vouchers, detailing packages, and merchandise.', 'Master of Ceremonies', 'Host', 10],
            ['12.45 - 13.00', '12:45', '13:00', 'Closing Remarks & Souvenir Distribution', 'Final remarks, commemorative gift handover, and photo session.', 'Master of Ceremonies', 'Host', 11],
        ];

        foreach ($agendas as $agenda) {
            EventAgenda::create([
                'event_id' => $event1->id,
                'time_start' => $agenda[1],
                'time_end' => $agenda[2],
                'title' => $agenda[3],
                'description' => $agenda[4],
                'speaker_name' => $agenda[5],
                'speaker_role' => $agenda[6],
                'sort_order' => $agenda[7],
            ]);
        }

        // Secondary Event: Mercedes-Benz Fleet Day
        $event2 = Event::create([
            'business_unit_id' => $mbUnit->id,
            'title' => 'Mercedes-Benz Commercial Vehicle Fleet Day 2026',
            'slug' => 'mercedes-benz-fleet-day-2026',
            'subtitle' => 'Heavy Duty Actros & Arocs Demonstration and Telematics Forum',
            'description' => 'Explore the next generation of transport efficiency with the latest Mercedes-Benz Actros and Arocs heavy-duty prime movers, paired with specialized body solutions from PT Rudy Darma Engineering.',
            'content_html' => '<h2>Pioneering Heavy Duty Logistics</h2><p>Experience the sheer power, safety, and telematics intelligence of the new Mercedes-Benz Actros & Arocs generation designed specifically for Indonesian industrial routes.</p>',
            'event_type' => 'exhibition',
            'visibility' => 'public',
            'date' => '2026-09-18',
            'start_time' => '09:00',
            'end_time' => '16:00',
            'timezone' => 'WIB',
            'venue_name' => 'PT Hartono Raya Motor Surabaya',
            'venue_address' => 'Jl. Demak No. 166-168, Gundih, Bubutan, Surabaya 60172',
            'venue_map_url' => 'https://maps.google.com/?q=Hartono+Raya+Motor+Surabaya',
            'dress_code' => 'Business Casual',
            'rsvp_contact' => 'HRM Fleet Sales',
            'rsvp_phone' => '+62 31 531 1306',
            'max_capacity' => 200,
            'registration_deadline' => '2026-09-15 17:00:00',
            'hero_image' => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&w=1600&q=80',
            'status' => 'published',
            'is_registration_enabled' => true,
            'is_guestbook_enabled' => true,
            'is_gallery_enabled' => true,
            'is_post_event_enabled' => true,
            'created_by' => $admin->id,
        ]);

        // 4. Hero Carousel Slides
        HeroSlide::create([
            'title' => 'Customer Gathering CV HRM & Soft Opening HR Auto Studio',
            'subtitle' => 'Celebrate the grand opening of our cutting-edge Body Repair & Detailing Center at Daan Mogot, Jakarta.',
            'badge_text' => 'Featured Gathering • Aug 27',
            'image_path' => 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=1600&q=80',
            'cta_text' => 'Confirm Your Attendance',
            'cta_url' => '/events/' . $event1->slug,
            'secondary_cta_text' => 'View Full Rundown',
            'secondary_cta_url' => '/events/' . $event1->slug,
            'event_id' => $event1->id,
            'show_countdown' => true,
            'countdown_date' => '2026-08-27',
            'countdown_time' => '10:00',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        HeroSlide::create([
            'title' => 'HR Auto Studio: Redefining Luxury Detailing & Body Restoration',
            'subtitle' => 'Factory-certified German paint booth, ceramic coatings, and high-precision collision repair facilities.',
            'badge_text' => 'Grand Soft Opening',
            'image_path' => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=1600&q=80',
            'cta_text' => 'Explore Media Gallery',
            'cta_url' => '/events/' . $event1->slug . '/gallery',
            'secondary_cta_text' => 'Read Press Release',
            'secondary_cta_url' => '/news/hartono-group-unveils-hr-auto-studio-jakarta-2026',
            'event_id' => $event1->id,
            'show_countdown' => false,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        HeroSlide::create([
            'title' => 'Mercedes-Benz Fleet Day 2026: Actros & Arocs Heavy Haulage',
            'subtitle' => 'Live demonstration of commercial vehicle telematics and customized trailers by PT Rudy Darma Engineering.',
            'badge_text' => 'Upcoming Expo • Sep 18',
            'image_path' => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?auto=format&fit=crop&w=1600&q=80',
            'cta_text' => 'Explore Fleet Day',
            'cta_url' => '/events/' . $event2->slug,
            'secondary_cta_text' => 'Media Kit',
            'secondary_cta_url' => '/media-kit',
            'event_id' => $event2->id,
            'show_countdown' => true,
            'countdown_date' => '2026-09-18',
            'countdown_time' => '09:00',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // 5. Event Media Gallery Items
        $galleryImages = [
            ['https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&w=1200&q=80', 'HR Auto Studio Detailing Bay & Ceramic Coating Station', 'gallery', 'image', true, 1],
            ['https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=1200&q=80', 'Mercedes-Benz Dealership Executive Showroom Jakarta', 'gallery', 'image', false, 2],
            ['https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=1200&q=80', 'Robotic Spray Paint Booth & Infrared Baking Bay', 'gallery', 'image', false, 3],
            ['https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1200&q=80', 'VIP Customer Gathering Arrival & Welcome Refreshments', 'post_event', 'image', false, 4],
            ['https://images.unsplash.com/photo-1542282088-72c9c27ed0cd?auto=format&fit=crop&w=1200&q=80', 'Ceremonial Ribbon-Cutting & Tumpeng Cutting Celebration', 'post_event', 'image', false, 5],
            ['https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'Official Soft Opening & Facility Tour Video Highlights', 'gallery', 'video_url', false, 6],
        ];

        foreach ($galleryImages as $media) {
            EventMedia::create([
                'event_id' => $event1->id,
                'uploaded_by' => $admin->id,
                'file_path' => $media[0],
                'caption' => $media[1],
                'collection' => $media[2],
                'type' => $media[3],
                'is_featured' => $media[4],
                'sort_order' => $media[5],
            ]);
        }

        // 6. Community Wishes & Afterthoughts
        EventComment::create([
            'event_id' => $event1->id,
            'user_id' => $member->id,
            'guest_name' => 'Bambang Soediro',
            'guest_company' => 'PT Nusantara Makmur Sentosa',
            'guest_role' => 'President Director',
            'message' => 'Congratulations to the Board and Team of PT Hartono Raya Motor! The new HR Auto Studio sets a whole new benchmark for automotive detailing and paint care in Indonesia. Looking forward to trusting our executive fleet here!',
            'photo_path' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
            'type' => 'wish',
            'likes_count' => 24,
            'is_approved' => true,
            'is_highlighted' => true,
        ]);

        EventComment::create([
            'event_id' => $event1->id,
            'user_id' => $newsAgency->id,
            'guest_name' => 'Reza Aditya',
            'guest_company' => 'AutonetMagz Editorial',
            'guest_role' => 'Senior Automotive Journalist',
            'message' => 'Spectacular launch ceremony today! The infrared paint curing booths and detailing facilities are on par with international standards. Wishing Hartono Group continuous everlasting growth!',
            'photo_path' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
            'type' => 'afterthought',
            'likes_count' => 18,
            'is_approved' => true,
            'is_highlighted' => true,
        ]);

        EventComment::create([
            'event_id' => $event1->id,
            'guest_name' => 'Dr. H. Suryo Pranoto',
            'guest_company' => 'Klinik Medika Utama',
            'guest_role' => 'Mercedes-Benz S-Class Owner',
            'message' => 'Selamat atas Soft Opening HR Auto Studio. Pelayanan HRM dari dulu selalu memuaskan dan fasilitas baru ini luar biasa megah.',
            'type' => 'wish',
            'likes_count' => 12,
            'is_approved' => true,
            'is_highlighted' => false,
        ]);

        // 7. Sample Registrations & QR codes
        $reg1 = Registration::create([
            'event_id' => $event1->id,
            'user_id' => $member->id,
            'registration_code' => 'HRM-VIP-2026',
            'full_name' => 'Bambang Soediro',
            'email' => 'member@hartonomotor-group.com',
            'phone' => '+62 812-8899-0011',
            'company' => 'PT Nusantara Makmur Sentosa',
            'attendee_type' => 'vip',
            'num_attendees' => 2,
            'vehicle_model' => 'Mercedes-Benz S 450 4MATIC',
            'license_plate' => 'B 1956 HRM',
            'notes' => 'Looking forward to the HR Auto Studio tour!',
            'status' => 'confirmed',
        ]);

        $reg2 = Registration::create([
            'event_id' => $event1->id,
            'user_id' => $newsAgency->id,
            'registration_code' => 'HRM-MED-7744',
            'full_name' => 'Reza Aditya',
            'email' => 'media@autonetmagz.com',
            'phone' => '+62 813-7766-5544',
            'company' => 'AutonetMagz',
            'attendee_type' => 'media',
            'media_outlet_name' => 'AutonetMagz Editorial',
            'num_attendees' => 2,
            'vehicle_model' => 'Mercedes-Benz C 300 AMG Line',
            'license_plate' => 'B 888 MAG',
            'status' => 'confirmed',
        ]);

        $reg3 = Registration::create([
            'event_id' => $event1->id,
            'registration_code' => 'HRM-GUEST-1001',
            'full_name' => 'Hendra Wijaya',
            'email' => 'hendra.wijaya@logistikindo.co.id',
            'phone' => '+62 818-0992-3344',
            'company' => 'PT Logistik Indo Express',
            'attendee_type' => 'general_guest',
            'num_attendees' => 1,
            'vehicle_model' => 'Mercedes-Benz Axor 2528 R',
            'status' => 'attended',
            'checked_in_at' => now()->subMinutes(15),
            'checked_in_by' => $organizer->id,
        ]);

        // 8. Guestbook messages
        $sampleSigners = [
            ['Bambang Soediro', 'PT Nusantara Makmur Sentosa', 'Congratulations to Hartono Group for the Grand Launching of HR Auto Studio! May this new facility continue to deliver world-class service standards.'],
            ['Reza Aditya', 'AutonetMagz Editorial', 'Proud to cover this monumental milestone. HR Auto Studio sets a new benchmark for automotive detailing in Indonesia.'],
            ['Hendra Wijaya', 'PT Logistik Indo Express', 'Sukses selalu untuk Hartono Raya Motor! Fasilitas body repair dan painting-nya sangat modern dan berkelas.'],
            ['Ir. Gunawan Santoso', 'Mercedes-Benz Club Indonesia (MBCI)', 'Selamat atas peresmian HR Auto Studio. Solusi terbaik untuk restorasi dan perawatan mobil premium.'],
            ['dr. Maya Kartika', 'Mayapada Healthcare Group', 'Acara yang sangat eksklusif dan tertata rapi. Selamat untuk Hartono Group atas ekspansi terbarunya.'],
            ['Michael Chandra', 'Chandra Transport Corp', 'Semoga Hartono Raya Motor semakin maju dan menjadi pilihan utama armada Mercedes-Benz di tanah air.'],
            ['Siti Nurhaliza', 'Warta Otomotif Nasional', 'Liputan yang sangat berkesan. Selamat atas peresmian fasilitas teknologi cat mutakhir Jerman.'],
            ['David Tanuwidjaja', 'PT Surya Prima Logistik', 'Fasilitas yang luar biasa lengkap dan canggih. Selamat untuk seluruh jajaran direksi dan manajemen.'],
            ['Agus Salim', 'PT Bintang Samudera Lines', 'Kemitraan yang terjalin selama ini sangat memuaskan. Sukses selalu untuk Hartono Group.'],
            ['Stephanie Widjaja', 'Luxury Auto Gallery Jakarta', 'Fasilitas detailing dan PPF bay-nya sangat steril dan berstandar internasional.'],
        ];

        foreach ($sampleSigners as $idx => $signer) {
            GuestBookEntry::create([
                'event_id' => $event1->id,
                'registration_id' => $idx === 0 ? $reg1->id : ($idx === 1 ? $reg2->id : ($idx === 2 ? $reg3->id : null)),
                'guest_name' => $signer[0],
                'company' => $signer[1],
                'message' => $signer[2],
                'is_approved' => true,
                'is_highlighted' => $idx < 3,
            ]);
        }

        // 9. Pre-defined Doorprizes with Multi-Winner Quantities
        \App\Models\Doorprize::create([
            'event_id' => $event1->id,
            'label' => 'Grand Prize',
            'description' => 'Apple iPhone 16 Pro Max 256GB + HR Auto Studio Diamond Ceramic Coating Package',
            'quantity' => 1,
            'sort_order' => 1,
        ]);

        \App\Models\Doorprize::create([
            'event_id' => $event1->id,
            'label' => '2nd Prize',
            'description' => 'Mercedes-Benz Chronograph Watch + Full Interior Detailing Voucher',
            'quantity' => 2,
            'sort_order' => 2,
        ]);

        \App\Models\Doorprize::create([
            'event_id' => $event1->id,
            'label' => '3rd Prize',
            'description' => 'HR Auto Studio Paint Protection Film (PPF) Voucher Rp 2.500.000',
            'quantity' => 3,
            'sort_order' => 3,
        ]);

        \App\Models\Doorprize::create([
            'event_id' => $event1->id,
            'label' => 'Consolation Doorprize',
            'description' => 'Exclusive Mercedes-Benz Leather Weekend Duffle Bag & Official Accessories Set',
            'quantity' => 5,
            'sort_order' => 4,
        ]);

        // 10. Press Releases
        PressRelease::create([
            'event_id' => $event1->id,
            'business_unit_id' => $studioUnit->id,
            'title' => 'Hartono Group Unveils HR Auto Studio: Redefining Luxury Vehicle Body Repair & Detailing in Jakarta',
            'slug' => 'hartono-group-unveils-hr-auto-studio-jakarta-2026',
            'summary' => 'PT Hartono Raya Motor marks a new era in premium automotive lifestyle with the soft opening of HR Auto Studio in Jakarta, featuring factory-certified paint technologies and luxury detailing.',
            'content' => "JAKARTA — PT Hartono Raya Motor, one of Indonesia's foremost automotive conglomerates since 1970, officially inaugurated HR Auto Studio Body Repair & Detailing at its premier Jakarta branch today.\n\nThe state-of-the-art facility integrates advanced German collision repair standards, computerized robotic paint matching, infrared curing chambers, and paint protection film (PPF) application bays.\n\n\"For over 50 years, Hartono Group has been dedicated to delivering unmatched automotive excellence. With HR Auto Studio, we complete our integrated one-stop ecosystem, providing our discerning clientele with the highest standard of body restoration and aesthetic care,\" said the Board of Directors representative during the inaugural ribbon-cutting ceremony.",
            'is_embargoed' => false,
            'visibility' => 'public',
            'published_at' => '2026-08-20 10:00:00',
            'views_count' => 142,
        ]);
    }
}
