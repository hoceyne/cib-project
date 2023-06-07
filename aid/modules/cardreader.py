from smartcard.System import readers
from smartcard.util import toHexString
from smartcard.CardRequest import CardRequest

# Create a card request object
card_request = CardRequest(timeout=10)

# Get the available card readers
readers = card_request.waitforcard()

# Connect to the card reader
connection = readers.connection

# Connect to the card
connection.connect()

# Select the application on the card (replace with appropriate AID)
SELECT_APDU = [0x00, 0xA4, 0x04, 0x00, 0x00]
response, sw1, sw2 = connection.transmit(SELECT_APDU)

# Check if the response was successful (status words 0x9000 indicate success)
if sw1 == 0x90 and sw2 == 0x00:
    # Read data from the card
    READ_DATA_APDU = [0x00, 0xB0, 0x00, 0x00, 0x00]
    response, sw1, sw2 = connection.transmit(READ_DATA_APDU)

    # Check if the response was successful
    if sw1 == 0x90 and sw2 == 0x00:
        # Convert response data to a hexadecimal string
        data = toHexString(response)
        print("Data:", data)
    else:
        print("Error reading data from the card.")
else:
    print("Error selecting application on the card.")

# Disconnect from the card
connection.disconnect()
