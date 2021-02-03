<template>
    <tbody>
        <tr v-for="entry in entries">
            <th scope="row">{{ entry.bib }}</th>
            <td>{{ entry.name }}</td>
            <td>{{ entry.status }}</td>
            <td>{{ entry.created }}</td>
            <td>
                <i v-if="entry.notes" class="ml-1 fas fa-sticky-note"></i>
            </td>
            <td>
                <div class="checkbox mb-3 ml-3">
                    <input :id="'mail-' + entry.entryid" type="checkbox"
                           :checked="entry.confirmationemail"
                           :disabled="entry.confirmationemail == 1"
                            @click="sendConfirmationEmail(entry.entryid)">
                    <label :for="'mail-' + entry.entryid"></label>
                </div>
            </td>
            <td>
                <div class="checkbox mb-3 ml-3">
                    <input :id="'approve-' + entry.entryid" type="checkbox">
                    <label :for="'approve-' + entry.entryid"></label>
                </div>
            </td>
            <td>
                <div class="checkbox mb-3 ml-2">
                    <input :id="'paid-' + entry.entryid" type="checkbox">
                    <label :for="'paid-' + entry.entryid"></label>
                </div>
            </td>
            <td>
                <div class="checkbox mb-3 ml-4">
                    <input :id="'confirmation-' + entry.entryid" type="checkbox">
                    <label :for="'confirmation-' + entry.entryid"></label>
                </div>
            </td>
            <td>
                <div class="checkbox mb-3 ml-3">
                    <input :id="'remove-' + entry.entryid" type="checkbox">
                    <label :for="'remove-' + entry.entryid"></label>
                </div>
            </td>
        </tr>
    </tbody>
</template>

<script>
    export default {
        name: "EventEntryDetailList",
        props: ['entries'],

        created() {
            console.log(this.entries)
        },
        methods: {
            sendConfirmationEmail: function(entryid) {

                // send email
            }
        }
    }
</script>

<style scoped>

</style>