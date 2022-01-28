//
//  QR+viewModel.swift
//  Streamly
//
//  Created by Rohit Singh on 11/2/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation
class QRViewModel: NSObject {

    var qrData:((QRResponse)->())?
    weak var delegate:HandleError?

    func getQrData() {
        ApiCall.getQRData(success: { data, _
            in
            if let qrModel = QRDataParser.parseContactData(data!) {
                self.qrData!((qrModel))
                ////debugPrint(epgModel)
                self.delegate?.successAPI()
            }
            //debugPrint("Success")
        }) { (error, data, code)  in
            if let errorResponse = data, let data = DataParser.parseFailLoginResponse(errorResponse) {
                print(data.message ?? "")
                self.delegate?.handleError(data.message ?? "", data.response_code ?? 0)
            } else {
            //debugPrint("failure")
                self.delegate?.handleError(error?.localizedDescription ?? "", code ?? 0)
            }
        }
    }
}

class QRDataParser {

    static func parseContactData(_ data: Data) -> QRResponse? {
        do {
            let contactModelElement = try JSONDecoder().decode(QRResponse.self, from: data)


            return contactModelElement
        } catch {
            //debugPrint(error)
        }
        return nil
    }
}
