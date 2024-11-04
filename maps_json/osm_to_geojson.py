import xmltodict
import json

# Data OSM Anda
osm_data = '''<osm version="0.6" generator="CGImap 0.9.3 (286371 spike-07.openstreetmap.org)" copyright="OpenStreetMap and contributors" attribution="http://www.openstreetmap.org/copyright" license="http://opendatacommons.org/licenses/odbl/1-0/">
 <node id="2518148509" visible="true" version="6" changeset="142160043" timestamp="2023-10-04T19:13:31Z" user="w_djatmiko" uid="6168168" lat="-7.1204411" lon="112.4155881">
  <tag k="admin_level" v="5"/>
  <tag k="name" v="Lamongan"/>
  <tag k="name:ja" v="ラモンガン"/>
  <tag k="place" v="city"/>
  <tag k="source" v="landsat"/>
 </node>
</osm>'''

# Parse XML to dictionary
data_dict = xmltodict.parse(osm_data)

# Extract node information
node = data_dict['osm']['node']
lat = float(node['@lat'])
lon = float(node['@lon'])

# Create properties dictionary with only 'name'
properties = {
    'name': next((tag['@v'] for tag in node['tag'] if tag['@k'] == 'name'), '')
}

# Create GeoJSON feature
feature = {
    "type": "Feature",
    "geometry": {
        "type": "Point",
        "coordinates": [lon, lat]  # Longitude, Latitude
    },
    "properties": properties
}

# Convert to GeoJSON string
geojson_str = json.dumps(feature, indent=2)

# Save to file
with open('coba2.geojson', 'w') as f:
    f.write(geojson_str)

print("GeoJSON file has been created: coba2.geojson")
