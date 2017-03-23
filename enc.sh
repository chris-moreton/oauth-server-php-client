# Add the environment variables in .env as encrypted .travis.yml variables

for OUTPUT in $(cat spec/.env)
do
echo Encrypting $OUTPUT
travis encrypt $OUTPUT -a
done

