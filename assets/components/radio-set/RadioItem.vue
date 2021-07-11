<template>
        <div class="image-block channel-container">
            <a href="#" @click.prevent="setChannel">
                <img
                        :src="radioChannel['iconPath']"
                        :alt="radioChannel['name']"
                        data-toggle="tooltip"
                        data-placement="left"
                        :title="radioChannel['name']"
                        class="img-thumbnail">
            </a>
        </div>
</template>

<script>

    import { eventBus } from '../eventBus/eventBus';

    export default {
        name: "RadioItem",
        props: {
            radioChannel: {
                type: Object,
                required: true,
            }
        },
        data() {
            return {};
        },
        methods: {
            // set selected channel to radio player
            // pass data to RadioPlayer component
            setChannel(e) {
                eventBus.$emit('setChannel', this.radioChannel);
            },
        },

        created() {
            console.log('radio item created');
        }
    }
</script>

<style scoped>
    .image-block {
        width: 150px;
        height: 150px;
    }

    .image-block img {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }
</style>
