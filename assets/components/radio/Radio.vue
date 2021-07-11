<template>
    <div>
        <RadioPlayer :cookieSettings="settings"></RadioPlayer>
        <RadioList></RadioList>
    </div>
</template>

<script>

    import { mapState } from 'vuex';
    import RadioList from '../radio-set/RadioList';
    import RadioPlayer from '../radio-player/RadioPlayer';

    export default {
        name: "Radio",
        components: { RadioList, RadioPlayer },
        data() {
            return {};
        },
        methods: {

        },
        computed: {
            // settings from cookie
            ...mapState(['settings']),
        },
        created() {
            console.log('radio created');
        }
    }
</script>
