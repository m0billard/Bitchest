<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\CryptoCurrency;
use App\Http\Controllers\CryptoCurrencyController;
use App\Models\UserRole;
use Illuminate\Database\Seeder;

class CryptoCurrencySeeder extends Seeder
{

  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // \App\Models\User::factory(10)->create();

    CryptoCurrency::create([
      'code' => 'BTC',
      'name' => 'Bitcoin',
      'description' => 'Bitcoin is a cryptocurrency, a virtual currency designed to act as money and a form of payment outside the control of any one person, group, or entity, thus removing the need for third-party involvement in financial transactions.',
      'current_price' => CryptoCurrencyController::getFirstCotation('Bitcoin'),
      'currency' => 'EUR'
    ]);
    CryptoCurrency::create([
      'code' => 'ETH',
      'name' => 'Ethereum',
      'description' => 'Ethereum is a decentralized blockchain network powered by the Ether token that enables users to make transactions, earn interest on their holdings through staking, use and store nonfungible tokens (NFTs), trade cryptocurrencies, play games, use social media and so much more.',
      'current_price' => CryptoCurrencyController::getFirstCotation('Ethereum'),
      'currency' => 'EUR'
    ]);
    CryptoCurrency::create([
      'code' => 'XRP',
      'name' => 'Ripple',
      'description' => 'XRP is a cryptocurrency and token Ripple Labs uses to facilitate transactions on its network. XRP primarily enhances global financial transfers and the exchange of several currencies. 1 Investors also use it to store value and profit from price fluctuations.',
      'current_price' => CryptoCurrencyController::getFirstCotation('Ripple'),
      'currency' => 'EUR'
    ]);
    CryptoCurrency::create([
      'code' => 'BCC',
      'name' => 'Bitcoin Cash',
      'description' => 'Bitcoin Cash is an altcoin version of the popular Bitcoin cryptocurrency. Bitcoin Cash is the result of a hard fork in blockchain technology. One of the most significant changes from Bitcoin to Bitcoin Cash is the size of the coin',
      'current_price' => CryptoCurrencyController::getFirstCotation('Bitcoin Cash'),
      'currency' => 'EUR'
    ]);
    CryptoCurrency::create([
      'code' => 'ADA',
      'name' => 'Cardano',
      'description' => 'Cardano is a proof-of-stake blockchain platform: the first to be founded on peer-reviewed research and developed through evidence-based methods. It combines pioneering technologies to provide unparalleled security and sustainability to decentralized applications, systems, and societies.',
      'current_price' => CryptoCurrencyController::getFirstCotation('Cardano'),
      'currency' => 'EUR'
    ]);
    CryptoCurrency::create([
      'code' => 'LTC',
      'name' => 'Litecoin',
      'description' => 'Litecoin is a peer-to-peer cryptocurrency that was set up by Charlie Lee (a former Google employee) in 2011. It shares many similarities with bitcoin and is based on bitcoin`s original source code. Litecoin was designed to be used for cheaper transactions, and to be more efficient for everyday use.',
      'current_price' => CryptoCurrencyController::getFirstCotation('Litecoin'),
      'currency' => 'EUR'
    ]);
    CryptoCurrency::create([
      'code' => 'XEM',
      'name' => 'NEM',
      'description' => 'Net Energy Metering is a system that helps customers reduce their dependence on fossil fuels by allowing them to use energy generated from renewable sources, like solar panels or wind turbines, to offset their electricity usage from the grid.',
      'current_price' => CryptoCurrencyController::getFirstCotation('NEM'),
      'currency' => 'EUR'
    ]);
    CryptoCurrency::create([
      'code' => 'XLM',
      'name' => 'Stellar',
      'description' => 'Stellar is a decentralized protocol on open-source code to transfer value domestically and across borders. The Stellar blockchain`s cryptocurrency is called the lumen, a token that trades under the symbol XLM.',
      'current_price' => CryptoCurrencyController::getFirstCotation('Stellar'),
      'currency' => 'EUR'
    ]);
    CryptoCurrency::create([
      'code' => 'MIOTA',
      'name' => 'IOTA',
      'description' => 'IOTA is a distributed ledger designed to record and execute transactions between machines and devices in the Internet of Things (IoT) ecosystem. The ledger uses a cryptocurrency called MIOTA to account for transactions in its network.',
      'current_price' => CryptoCurrencyController::getFirstCotation('IOTA'),
      'currency' => 'EUR'
    ]);
    CryptoCurrency::create([
      'code' => 'DASH',
      'name' => 'Dash',
      'description' => 'Dash is a digital currency that offers fast, cheap payments anywhere in the world. It aims to provide a user-friendly experience and privacy equal to cash. Software developer Evan Duffield launched Dash on Jan. 18, 2014, under its original name of XCoin, which was later changed to Darkcoin.',
      'current_price' => CryptoCurrencyController::getFirstCotation('Dash'),
      'currency' => 'EUR'
    ]);
  }
}
