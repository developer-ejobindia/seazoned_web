<p>we are sorry to inform you that the provider is unable to fulfill your request at this time. 
Please search for another provider</p><br><br>

<p>
Hello, This is to infom you that Service Provider: <b>{{ $data['provider_name'] }}</b> 
has rejected the service of <b>{{ $data['customer_name'] }}</b><br><br>

<p>Hello Seazoned customer. Regretfully, we are notifying you because the provider (<b>{{ $data['provider_name'] }}</b>) 
is unable to complete the service (<b>{{ $data['service_name'] }}</b>) and has declined the opportunity.</p><br>

<p>As this is uncommon, we apologize for the inconvenience and invite you to book another provider.</p>

<b>Customer Details :</b><br> 
Customer Name: {{ $data['customer_name'] }} <br>
Customer Address: {{ $data['customer_address'] }} <br>
Service Name: {{ $data['service_name'] }} <br>
Service Date: {{ $data['service_date'] }} <br>
Service Time: {{ $data['service_time'] }} <br>
Order No: {{ $data['order_no'] }} <br> <br>
<b>Provider Details :</b><br> 
Provider Name: {{ $data['provider_name'] }} <br>
Person Name: {{ $data['person_name'] }} <br>
Provider Address: {{ $data['landscaper_address'] }} <br>
Provider Phone: {{ $data['landscaper_phone'] }} <br>
Provider Email: {{ $data['landscaper_email'] }} <br>
<br>
Warm regards,<br>
Seazoned Team  
</p>