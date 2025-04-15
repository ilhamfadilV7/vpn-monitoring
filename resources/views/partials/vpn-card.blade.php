<div class="col mb-3">
    <div class="card border border-5 {{ $data['status'] === 'Connected' ? 'card-border-success' : 'card-border-danger' }}">
        <div class="card-body">
            <div class="d-flex align-items-center mb-2">
                <div class="flex-grow-1">
                    <h5 class="fs-md mb-1">{{ $data['name'] }}</h5>
                    <span class="badge {{ $data['status'] === 'Connected' ? 'bg-bright-green' : 'bg-bright-red' }}">
                        {{ $data['status'] }}
                    </span>
                </div>
            </div>
            <div>
                <p class="mb-1"><strong>Service:</strong> {{ $data['service'] }}</p>
                <p class="mb-1"><strong>IP Address:</strong> {{ $data['address'] }}</p>
                <p class="mb-1"><strong>Uptime:</strong> {{ $data['uptime_formatted'] }}</p>
            </div>
        </div>
    </div>
</div>