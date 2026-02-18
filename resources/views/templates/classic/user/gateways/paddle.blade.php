<!DOCTYPE html>
<html>
<body>
<script src="https://cdn.paddle.com/paddle/paddle.js"></script>
<script>
    Paddle.Setup(
        {
            vendor: @json($gateway->credentials->vendor_id),
            eventCallback: function (data) {
                if (data.event === "Checkout.Close") {
                    window.location.href = @json(route('subscription'));
                    return false;
                }
            }
        }
    );

    @if($gateway->test_mode)
    Paddle.Environment.set('sandbox');
    @endif

    Paddle.Checkout.open({
        override: @json($redirect_url)
    });
</script>
</body>
</html>
