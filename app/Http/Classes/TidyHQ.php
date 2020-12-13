<?php


namespace App\Http\Classes;


use App\Models\TidyHqContact;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TidyHQ
{

    protected string $clientId = '';
    protected string $clientSecret = '';
    protected string $redirect = '';
    protected string $accessToken = '';

    const BASEURL = 'https://api.tidyhq.com/v1/';


    public function __construct()
    {

        if (getenv('APP_DEBUG') == true) {
            $this->clientId = getenv('TIDYHQ_CLIENTID_BUILD');
            $this->clientSecret = getenv('TIDYHQ_CLIENTSECRET_BUILD');
            $this->redirect = getenv('TIDYHQ_REDIRECT_BUILD');
        }
        else {
            $this->clientId = getenv('TIDYHQ_CLIENTID');
            $this->clientSecret = getenv('TIDYHQ_CLIENTSECRET');
            $this->redirect = getenv('TIDYHQ_REDIRECT');
        }

        $this->accessToken = $this->getAccessToken();

        if (empty($this->clientSecret) || empty($this->clientId)) {
            Log::error('Invalid TidyHQ data');
        }
    }

    protected function getAccessToken()
    {
        $token = DB::select("
            SELECT *
            FROM `oauthservices`
            WHERE `service` = 'TidyHQ'
        ");

        if (empty($token)) {
            Log::error('Missing Token');
            return '';
        }

        $token = reset($token);

        return $token->token;
    }

    public function login()
    {
        return redirect('https://accounts.tidyhq.com/oauth/authorize?client_id=' . $this->clientId . '&redirect_uri=' . $this->redirect . '&response_type=token');
    }

    protected function call($method, $path, $options = null)
    {
        $request = Http::withToken($this->accessToken);

        switch (strtolower($method)) {

            case 'get':
                $response = $request->get(static::BASEURL . $path);
                break;

            case 'post' :
                $response = $request->post(static::BASEURL . $path, $options);
                break;

            default:
                break;
        }

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Invalid response');

    }

    public function getAllContacts()
    {
        return $this->call('get', 'contacts');
    }

    public function updateAllContacts()
    {
        $contacts = $this->getAllContacts();

        if (empty($contacts)) {
            return null;
        }

        $users = User::all()->keyBy('email')->toArray();

        $users = array_change_key_case($users);

        foreach ($contacts as $contact) {

            $contact = (object) $contact;

            $userid = null;
            if (!empty($contact->email_address) && isset($users[$contact->email_address])) {
                $userid = $users[$contact->email_address]['userid'];
            }


            $customFields = [];

            if (!empty($contact->custom_fields)) {

                foreach ($contact->custom_fields as $custom_field) {

                    $key = ($custom_field['title'] ?? '');
                    $value = null;

                    if (!array_key_exists('value', $custom_field)) {
                        continue;
                    }

                    // Bool
                    if (is_bool($custom_field['value'])) {
                        $value = $custom_field['value'];
                    }
                    // String
                    else if (is_string($custom_field['value'])) {
                        $value = $custom_field['value'];
                    }
                    // Array
                    else if (is_array($custom_field['value'])) {

                        if (array_key_exists('title', $custom_field['value'])) {
                            $value = $custom_field['value']['title'];
                        }
                        else {

                            $value = [];

                            foreach ($custom_field['value'] as $field) {
                                $value[] = $field['title'];
                            }

                            $value = implode(',', $value);
                        }

                    }

                    $customFields[$key] = $value;

                }
            }

            $contactData = [
                'id' => $contact->id,
                'userid' => $userid,
                'firstname' => $contact->first_name,
                'lastname' => $contact->last_name,
                'nickname' => $contact->nick_name,
                'company' => $contact->company,
                'email' => $contact->email_address,
                'phone' => $contact->phone_number,
                'address1' => $contact->address1,
                'city' => $contact->city,
                'state' => $contact->state,
                'postcode' => $contact->postcode,
                'country' => $contact->country,
                'gender' => $contact->gender,
                'birthday' => $contact->birthday,
                'details' => $contact->details,
                'subscribed' => (int) $contact->subscribed,
                'thqcreatedat' => date('Y-m-d H:i:s', strtotime($contact->created_at)),
                'thqupdatedat' => date('Y-m-d H:i:s', strtotime($contact->updated_at)),
                'contactid' => $contact->contact_id,
                'contactidnumber' => $contact->contact_id_number,
                'membershipnumber' => $contact->contact_id_number,
                'contactstatus' => $contact->status,
                'customfields' => json_encode($customFields),
            ];

            TidyHqContact::updateOrCreate(
                [ 'id'=> $contact->id ],
                $contactData
            );
        }

        return true;
    }

    public function getMemberships()
    {
        return $this->call('get', 'memberships');
    }


    public function updateMemberships()
    {
        $memberships = $this->getMemberships();

        if (empty($memberships)) {
            return false;
        }

        foreach ($memberships as $membership) {

           $membership = (object) $membership;

            if (empty($membership->contact_id)) {
                continue;
            }

            $contact = TidyHqContact::where('id', $membership->contact_id)->first();

            if (empty($contact)) {
                continue;
            }

            $contact->membershipstatus = $membership->state;
            $contact->membershipid = $membership->id;
            $contact->save();
        }

    }

}
