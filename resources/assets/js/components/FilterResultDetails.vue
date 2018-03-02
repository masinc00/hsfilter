<template>
    <table class="FilterResultDetails">
        <tr v-for="item in value">
            <th><!-- todo: key名を表示 --></th>
            <td>{{ item }}</td>
        </tr>
    </table>
</template>

<script>
    export default {
        name: "FilterResultDetails",
        props: ["value"],
        
    }
</script>

<style>
    .visible{
        display: none;
    }
</style>