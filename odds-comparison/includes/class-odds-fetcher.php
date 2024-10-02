<?php

// class Odds_Fetcher {

//     public static function fetch_odds( $bookmakers ) {
//         // Placeholder data. You will need to implement web scraping logic here.
//         $odds = array(
//             'Bookmaker A' => '2.5',
//             'Bookmaker B' => '2.7',
//             'Bookmaker C' => '2.9',
//         );

//         return $odds;
//     }
// }

class Odds_Fetcher {

    public static function fetch_odds( $bookmakers ) {
        // Example using an API (replace with actual API logic)
        $api_url = "https://api.the-odds-api.com/v3/odds/?sport=soccer&region=uk&mkt=h2h&apiKey=YOUR_API_KEY";
        
        $response = wp_remote_get( $api_url );
        if ( is_wp_error( $response ) ) {
            return []; // Handle error
        }

        $data = wp_remote_retrieve_body( $response );
        $odds = json_decode( $data, true );

        // Process the data as needed
        return $odds;
    }
}
