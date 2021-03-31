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
    func handleError(_ message: String) {
        self.removeSpinner()
        self.showSimpleAlertWithMessage("Something went wrong!, Please try again")
    }

}
