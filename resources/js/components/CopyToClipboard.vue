<template>
    <div class="container">
        <button class="btn btn-primary" @click="copyToClipboard"><strong>Copy</strong></button>
    </div>
</template>
<script>

    export default {

        //copy url to clipboard
        methods: {
            copyToClipboard() {
                let url = window.location.href;
                let el = document.createElement('textarea');
                el.value = url;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                alert('URL copied to clipboard');
            }
        }

    }
</script>