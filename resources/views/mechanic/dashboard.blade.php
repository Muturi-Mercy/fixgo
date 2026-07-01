<!DOCTYPE html>
<html>
<head><title>Mechanic Dashboard - FixGo</title></head>
<body>
    <h1>Welcome Mechanic, {{ auth()->user()->name }}</h1>
    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
</body>
</html>