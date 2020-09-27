<template>
    <li>
        <div class="inbox-item-img">
            <img src="" class="rounded-circle" alt="">
        </div>
        <p class="inbox-item-author">{{entry}}</p>
        <p class="inbox-item-text">I've finished it! See you so...</p>
        <p class="inbox-item-date">
            <a href="javascript:(0);" class="btn btn-sm btn-link text-info font-13"> Reply </a>
        </p>
    </li>
</template>

<script>
    export default {
        name: "EventEntryDetailListItem",
        props: ['entry'],

        created() {
            console.log(this.entry)
        }
    }
</script>

<style scoped>

</style>