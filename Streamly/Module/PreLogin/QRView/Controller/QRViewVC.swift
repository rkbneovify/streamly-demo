//
//  QRViewVC.swift
//  Streamly
//
//  Created by Rohit Singh on 11/2/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit
import SDWebImage

class QRViewVC: UIViewController {

    //MARK: Outlets
    @IBOutlet weak var qrView:UIImageView!
    @IBOutlet weak var urlLBL:UILabel!

    //MARK: Properties

    var isFetchData = false
    var qrData:QRResponse?
    lazy var viewModel: QRViewModel = {
        return QRViewModel()
    }()


    //MARK: Life Cycle
    override func viewDidLoad() {
        super.viewDidLoad()
        self.initialSetup()

        // Do any additional setup after loading the view.
    }
    
    private func initialSetup()
    {
        if isFetchData
        {
            self.reLoadView()
        }
        else {
            self.makeAPiCall()
        }
        
        
    }

    private func reLoadView()
    {
        if let data = qrData
        {
            self.qrView.sd_setImage(with: URL(string: data.data ?? ""), placeholderImage: UIImage(named: "dummyQR"))
            self.urlLBL.text = data.message ?? ""
        }
        // Get define string to encode
//        let myString = "https://pennlabs.org"
//        // Get data from the string
//        let data = myString.data(using: String.Encoding.ascii)
//        // Get a QR CIFilter
//        guard let qrFilter = CIFilter(name: "CIQRCodeGenerator") else { return }
//        // Input the data
//        qrFilter.setValue(data, forKey: "inputMessage")
//        // Get the output image
//        guard let qrImage = qrFilter.outputImage else { return }
//        // Scale the image
//        let transform = CGAffineTransform(scaleX: 10, y: 10)
//        let scaledQrImage = qrImage.transformed(by: transform)
//        // Invert the colors
//        guard let colorInvertFilter = CIFilter(name: "CIColorInvert") else { return }
//        colorInvertFilter.setValue(scaledQrImage, forKey: "inputImage")
//        guard let outputInvertedImage = colorInvertFilter.outputImage else { return }
//        // Replace the black with transparency
//        guard let maskToAlphaFilter = CIFilter(name: "CIMaskToAlpha") else { return }
//        maskToAlphaFilter.setValue(outputInvertedImage, forKey: "inputImage")
//        guard let outputCIImage = maskToAlphaFilter.outputImage else { return }
//        // Do some processing to get the UIImage
//        let context = CIContext()
//        guard let cgImage = context.createCGImage(outputCIImage, from: outputCIImage.extent) else { return }
//        let processedImage = UIImage(cgImage: cgImage)
//        self.qrView.image = processedImage

        //https://medium.com/@dominicfholmes/generating-qr-codes-in-swift-4-b5dacc75727c
    }
    
    private func makeAPiCall()
    {
        self.showSpinner(onView: self.view)
        viewModel.getQrData()
        viewModel.delegate = self
        self.initVM()
    }

    private func initVM()
    {
        viewModel.qrData = { qryModel in
            self.removeSpinner()
            self.qrData = qryModel
            self.reLoadView()


        }
    }


}

extension QRViewVC:HandleError
{
    func handleError(_ message: String, _ statusCode: Int) {
        if statusCode == 402 {
            showAlert(withMessage: "We're having some trouble with your current subscription. We'll try again, but in meantime you may want to update your subscription. For more help, please visit streamlytv.com or contact at https://streamlytv.com/helpandsupport.",setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.logOut()
            }
            return
        }
        self.removeSpinner()
        self.showSimpleAlertWithMessage("Something went wrong!, Please try again")
    }

}
