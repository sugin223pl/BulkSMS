<template>
  <div class="home">
    <b-row>
      <b-col sm="8">
        <b-row>
          <b-col sm="12">
            <h2>Bulk SMS</h2>
          </b-col>
          <b-col sm="12">
            <b-input-group prepend="Numere" class="mt-3">
              <b-form-file
                accept="text/plain"
                :input="input"
                v-model="fisier"
                :state="Boolean(fisier)"
                placeholder="Choose a file or drop it here..."
                drop-placeholder="Drop file here..."
                @change="handleFile"
                id="numere"
              ></b-form-file>
            </b-input-group>
          </b-col>
          <b-col sm="12">
            <b-form-textarea style="margin-top: 10px;"
              id="textarea"
              v-model="text"
              placeholder="Enter something..."
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-col>
          <b-col>
            <b-form-group style="margin-top: 15px;">
              <b-form-checkbox-group
                v-model="selected"
                :options="options"
                switches
              ></b-form-checkbox-group>
            </b-form-group>
          </b-col>
          <b-col sm="2">
            <b-form-select v-model="slot" :options="slot_options" style="margin-top: 10px;"></b-form-select>
          </b-col>
          <b-col>
            <b-button block  style="margin-top: 10px;" variant="danger">Trimite SMS</b-button>
          </b-col>
        </b-row>
      </b-col>
      <b-col sm="4">
        <b-row>
          <b-col sm="12">
            <h2>Numere</h2>
          </b-col>
          <b-col sm="12">
            <table style="width: 100%;">
              <thead>
                <tr>
                  <th>#</th>
                </tr>
              </thead>
              <tbody v-if="numere.length">
                <tr v-for="(numar, index) in numere" :key="(index + 1)">
                  <td><pre>{{ (index + 1) }}. {{numar}}</pre></td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr>
                  <td>
                    <p class="text-muted">0 numere</p>
                    <p class="text-muted">{{selected}}</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
  </div>
</template>
<script>
export default {
  name: 'Home',
  data() {
    return {
      slot: 1,
      slot_options: [
        { value: 1, text: 'SIM 1' },
        { value: 2, text: 'SIM 2' },
      ],
      selected: [],
      input: [],
      fisier: [],
      numere: [],
      text: '',
      options: [
        { text: 'Delivery Report', value: 1 }
      ]
    };
  },
  methods: {
    handleFile(event) {
      const file = event.target.files[0];
      if (file.name != null) {
        const reader = new FileReader();
        var text;
        reader.onload = (e) => {
          text = e.target.result;
          var lines = text.split('\n');
          lines = lines.filter(String);
          lines.forEach( (line) => {
            line = line.replace(/ /g, "");
            line = line.replace('+', '00');
            console.log(line)
            this.numere.push(line);
          })
        };
        reader.readAsText(file, 'UTF-8');
      }
    },
  },
};
</script>
<style lang="css">
  pre {
    margin-bottom: 0 !important;
  }
</style>
