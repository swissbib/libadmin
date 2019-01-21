# Libadmin Export Api
---------------------

## Export Formats

The libadmin export API support all types of formats which are implemented by the requested system.
Currently the following formats are enabled in routing (module.config.php):

* JSON
* XML

## Export Systems

You can implement custom export systems. To register a new system, add it as service configuration to your module.
The service name has to follow this format:

	export_system_YOURSYSTEMNAME

Example: export_system_vufind


## API URL

The service is located under the **/api** URL and has the following format:

	/api/{SYSTEM}/{VIEW].{FORMAT}[?option[a]=1&option[b]=2]

Simple examples

	/api/vufind/green.json
	/api/vufind/orange.xml
Advanced example

	/api/maps/libmap.json?option[canton]=zh,bl,bs,ag
	
## GeoJson

    /api/geojson/green.json?option[lang]=de
