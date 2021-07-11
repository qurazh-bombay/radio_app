<template>
    <div class="row mt-5 d-flex justify-content-center">
        <template v-if="radioList.length > 0">
            <RadioItem
                    v-for="radio in radioList"
                    :key ="radio.id"
                    :radioChannel="radio"></RadioItem>
        </template>
        <template v-else>

                <p class="h3 text-primary" >Radio channel list is empty</p>

        </template>
    </div>
</template>

<script>

    import { mapState } from 'vuex';
    import RadioItem  from './RadioItem';

    export default {
        name: "RadioList",
        components: { RadioItem },
        data() {
            return {};
        },
        computed: {
            // get list of radio channels
            ...mapState(['radioList'])
        },
        created() {
            console.log('radio list created');
        }
    }
</script>
