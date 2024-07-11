
<!DOCTYPE html>
<html>
<head>
    <title>VLSM Calculator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">VLSM Calculator</h1>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('calculate') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="ip">IP Address</label>
                <input type="text" class="form-control" id="ip" name="ip" required>
            </div>
            <div class="form-group">
                <label for="mask">Mask</label>
                <input type="number" class="form-control" id="mask" name="mask" required>
            </div>
            <div id="subnet-fields">
                <div class="form-group">
                    <label for="subnet_name">Subnet Name</label>
                    <input type="text" class="form-control" name="subnet_name[]" required>
                    <label for="hosts_needed">Hosts Needed</label>
                    <input type="number" class="form-control" name="hosts_needed[]" required>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="add-subnet">Add Subnet</button>
            <button type="submit" class="btn btn-success">Calculate</button>
        </form>
    </div>

    <script>
        document.getElementById('add-subnet').addEventListener('click', function() {
            var subnetFields = document.getElementById('subnet-fields');
            var newField = document.createElement('div');
            newField.classList.add('form-group');
            newField.innerHTML = `
                <label for="subnet_name">Subnet Name</label>
                <input type="text" class="form-control" name="subnet_name[]" required>
                <label for="hosts_needed">Hosts Needed</label>
                <input type="number" class="form-control" name="hosts_needed[]" required>
            `;
            subnetFields.appendChild(newField);
        });
    </script>
</body>
</html>